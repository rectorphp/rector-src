<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BooleanType;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\BoolReturnTypeFromStrictScalarReturnsRectorTest
 */
final class BoolReturnTypeFromStrictScalarReturnsRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReturnAnalyzer $returnAnalyzer,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ValueResolver $valueResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change return type based on strict returns type operations', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve($first, $second)
    {
        if ($first) {
            return false;
        }

        return $first > $second;
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
            return false;
        }

        return $first > $second;
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
        return [ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param ClassMethod|Function_|Closure $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($this->shouldSkip($node, $scope)) {
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
     * @param ClassMethod|Function_|Closure $node
     */
    private function shouldSkip(Node $node, Scope $scope): bool
    {
        if ($node->returnType instanceof Node) {
            return true;
        }

        return $node instanceof ClassMethod
            && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod($node, $scope);
    }

    /**
     * @param Return_[] $returns
     */
    private function hasOnlyBoolScalarReturnExprs(array $returns, ClassMethod|Function_|Closure $functionLike): bool
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

        if ($expr instanceof BooleanOr) {
            return true;
        }

        if ($expr instanceof BooleanAnd) {
            return true;
        }

        if ($expr instanceof Identical) {
            return true;
        }

        if ($expr instanceof NotIdentical) {
            return true;
        }

        if ($expr instanceof Equal) {
            return true;
        }

        return $expr instanceof NotEqual;
    }
}
