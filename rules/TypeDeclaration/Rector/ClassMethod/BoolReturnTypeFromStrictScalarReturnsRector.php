<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BooleanType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\BoolReturnTypeFromStrictScalarReturnsRectorTest
 */
final class BoolReturnTypeFromStrictScalarReturnsRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReturnAnalyzer $returnAnalyzer,
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change funcCall type based on strict returns type operations', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve($first, $second)
    {
        if ($first) {
            funcCall false;
        }

        funcCall $first > $second;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve($first, $second): bool
    {
        if ($first) {
            funcCall false;
        }

        funcCall $first > $second;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @funcCall array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->returnType instanceof Node) {
            return null;
        }

        $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($node, Return_::class);
        if (! $this->hasOnlyBoolScalarReturnExprs($returns, $node)) {
            return null;
        }

        $node->returnType = new Identifier('bool');

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }

    /**
     * @param Return_[] $returns
     */
    private function hasOnlyBoolScalarReturnExprs(array $returns, ClassMethod|Function_ $functionLike): bool
    {
        if ($returns === []) {
            return false;
        }

        if (! $this->returnAnalyzer->hasClassMethodRootReturn($functionLike)) {
            return false;
        }

        foreach ($returns as $return) {
            if (! $return->expr instanceof Expr) {
                return false;
            }

            if ($this->valueResolver->isTrueOrFalse($return->expr)) {
                continue;
            }

            if ($this->isBooleanBinaryOp($return->expr)) {
                continue;
            }

            if ($return->expr instanceof FuncCall && $this->isNativeBooleanReturnTypeFuncCall($return->expr)) {
                continue;
            }

            return false;
        }

        return true;
    }

    private function isNativeBooleanReturnTypeFuncCall(FuncCall $funcCall): bool
    {
        $functionName = $this->getName($funcCall);

        if (! is_string($functionName)) {
            return false;
        }

        $functionReflection = $this->reflectionProvider->getFunction(new Name($functionName), null);
        if (! $functionReflection->isBuiltin()) {
            return false;
        }

        foreach ($functionReflection->getVariants() as $parametersAcceptorWithPhpDoc) {
            return $parametersAcceptorWithPhpDoc->getNativeReturnType() instanceof BooleanType;
        }

        return false;
    }

    private function isBooleanBinaryOp(Expr $expr): bool
    {
        if ($expr instanceof Smaller) {
            return true;
        }

        if ($expr instanceof SmallerOrEqual) {
            return true;
        }

        if ($expr instanceof Greater) {
            return true;
        }

        if ($expr instanceof GreaterOrEqual) {
            return true;
        }

        if ($expr instanceof Expr\BinaryOp\BooleanOr) {
            return true;
        }

        if ($expr instanceof Expr\BinaryOp\BooleanAnd) {
            return true;
        }
        if ($expr instanceof Expr\BinaryOp\Identical) {
            return true;
        }
        if ($expr instanceof Expr\BinaryOp\NotIdentical) {
            return true;
        }

        if ($expr instanceof Expr\BinaryOp\Equal) {
            return true;
        }
        return $expr instanceof Expr\BinaryOp\NotEqual;
    }
}
