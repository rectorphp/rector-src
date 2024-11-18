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
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\BoolReturnTypeFromBooleanStrictReturnsRectorTest
 */
final class BoolReturnTypeFromBooleanStrictReturnsRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ValueResolver $valueResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
        private readonly ReturnAnalyzer $returnAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add bool return type based on strict bool returns type operations', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve($first, $second)
    {
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
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $scope = ScopeFetcher::fetch($node);
        if ($this->shouldSkip($node, $scope)) {
            return null;
        }

        $returns = $this->betterNodeFinder->findReturnsScoped($node);
        if (! $this->returnAnalyzer->hasOnlyReturnWithExpr($node, $returns)) {
            return null;
        }

        // handled in another rule
        if ($this->hasOnlyBooleanConstExprs($returns)) {
            return null;
        }

        // handled in another rule
        if (! $this->hasOnlyBoolScalarReturnExprs($returns)) {
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
        // already has the type, skip
        if ($node->returnType instanceof Node) {
            return true;
        }

        return $node instanceof ClassMethod
            && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod($node, $scope);
    }

    /**
     * @param Return_[] $returns
     */
    private function hasOnlyBoolScalarReturnExprs(array $returns): bool
    {
        foreach ($returns as $return) {
            if (! $return->expr instanceof Expr) {
                return false;
            }

            if ($this->isBooleanOp($return->expr)) {
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

        $name = new Name($functionName);
        if (! $this->reflectionProvider->hasFunction($name, null)) {
            return false;
        }

        $functionReflection = $this->reflectionProvider->getFunction($name, null);
        if (! $functionReflection->isBuiltin()) {
            return false;
        }

        foreach ($functionReflection->getVariants() as $variant) {
            if (! $variant->getNativeReturnType()->isBoolean()->yes()) {
                return false;
            }
        }

        return true;
    }

    private function isBooleanOp(Expr $expr): bool
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

        if ($expr instanceof NotEqual) {
            return true;
        }

        if ($expr instanceof Empty_) {
            return true;
        }

        if ($expr instanceof Isset_) {
            return true;
        }

        return $expr instanceof BooleanNot;
    }

    /**
     * @param Return_[] $returns
     */
    private function hasOnlyBooleanConstExprs(array $returns): bool
    {
        foreach ($returns as $return) {
            if (! $return->expr instanceof ConstFetch) {
                return false;
            }

            if (! $this->valueResolver->isTrueOrFalse($return->expr)) {
                return false;
            }
        }

        return true;
    }
}
