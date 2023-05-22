<?php

declare(strict_types=1);

namespace Rector\Php71\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\Cast\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ArrayType;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Core\NodeAnalyzer\VariableAnalyzer;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\TypeAnalyzer\CountableTypeAnalyzer;
use Rector\Php71\NodeAnalyzer\CountableAnalyzer;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://3v4l.org/Bndc9
 *
 * @see \Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\CountOnNullRectorTest
 */
final class CountOnNullRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly CountableTypeAnalyzer $countableTypeAnalyzer,
        private readonly CountableAnalyzer $countableAnalyzer,
        private readonly VariableAnalyzer $variableAnalyzer,
        private readonly PhpVersionProvider $phpVersionProvider
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::COUNT_ON_NULL;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes count() on null to safe ternary check',
            [new CodeSample(
                <<<'CODE_SAMPLE'
$values = null;
$count = count($values);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$values = null;
$count = $values === null ? 0 : count($values);
CODE_SAMPLE
            )]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $firstArg = $node->getArgs()[0];

        $countedNode = $firstArg->value;
        if ($this->countableTypeAnalyzer->isCountableType($countedNode)) {
            return null;
        }

        // this can lead to false positive by phpstan, but that's best we can do
        $onlyValueType = $this->getType($countedNode);
        if ($onlyValueType instanceof ArrayType) {
            if (! $this->countableAnalyzer->isCastableArrayType($countedNode, $onlyValueType, $scope)) {
                return null;
            }

            return $this->castToArray($countedNode, $node);
        }

        if ($this->nodeTypeResolver->isNullableTypeOfSpecificType($countedNode, ArrayType::class)) {
            return $this->castToArray($countedNode, $node);
        }

        if ($this->isAlwaysIterableType($onlyValueType)) {
            return null;
        }

        if ($this->nodeTypeResolver->isNullableType($countedNode) || $onlyValueType instanceof NullType) {
            $identical = new Identical($countedNode, $this->nodeFactory->createNull());

            return new Ternary($identical, new LNumber(0), $node);
        }

        if ($this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::IS_COUNTABLE)) {
            $conditionNode = new FuncCall(new Name('is_countable'), [new Arg($countedNode)]);
        } else {
            $instanceof = new Instanceof_($countedNode, new FullyQualified('Countable'));
            $conditionNode = new BooleanOr($this->nodeFactory->createFuncCall(
                'is_array',
                [new Arg($countedNode)]
            ), $instanceof);
        }

        return new Ternary($conditionNode, $node, new LNumber(0));
    }

    private function isAlwaysIterableType(Type $possibleUnionType): bool
    {
        if ($possibleUnionType->isIterable()->yes()) {
            return true;
        }

        if (! $possibleUnionType instanceof UnionType) {
            return false;
        }

        $types = $possibleUnionType->getTypes();

        foreach ($types as $type) {
            if ($type->isIterable()->no()) {
                return false;
            }
        }

        return true;
    }

    private function shouldSkip(FuncCall $funcCall): bool
    {
        if (! $this->isName($funcCall, 'count')) {
            return true;
        }

        if (! isset($funcCall->getArgs()[0])) {
            return true;
        }

        $firstArg = $funcCall->getArgs()[0];

        if ($firstArg->value instanceof ClassConstFetch) {
            return true;
        }

        // skip node in trait, as impossible to analyse
        $trait = $this->betterNodeFinder->findParentType($funcCall, Trait_::class);
        if ($trait instanceof Trait_) {
            return true;
        }

        $firstArg = $funcCall->getArgs()[0];
        if (! $firstArg->value instanceof Variable) {
            return false;
        }

        return $this->variableAnalyzer->isStaticOrGlobal($firstArg->value);
    }

    private function castToArray(Expr $countedExpr, FuncCall $funcCall): FuncCall
    {
        $castArray = new Array_($countedExpr);
        $funcCall->args = [new Arg($castArray)];

        return $funcCall;
    }
}
