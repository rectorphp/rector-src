<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
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
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstReturnsRector\BoolReturnTypeFromBooleanConstReturnsRectorTest
 */
final class BoolReturnTypeFromBooleanConstReturnsRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ValueResolver $valueResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
        private readonly ReturnAnalyzer $returnAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add return bool, based on direct true/false returns', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve($value)
    {
        if ($value) {
            return false;
        }

        return true;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve($value): bool
    {
        if ($value) {
            return false;
        }

        return true;
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

        if (! $this->hasOnlyBooleanConstExprs($returns)) {
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
