<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Analyser\Scope;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\NumericReturnTypeFromStrictScalarReturnsRectorTest
 */
final class NumericReturnTypeFromStrictScalarReturnsRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ReturnAnalyzer $returnAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add int/float return type based on strict scalar returns type', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function getNumber()
    {
        return 200;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function getNumber(): int
    {
        return 200;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
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

        $isAlwaysInt = true;
        $isAlwaysFloat = true;

        foreach ($returns as $return) {
            $expr = $return->expr;
            if ($expr instanceof UnaryMinus) {
                $expr = $expr->expr;
            }

            if (! $expr instanceof DNumber) {
                $isAlwaysFloat = false;
            }

            if (! $expr instanceof LNumber) {
                $isAlwaysInt = false;
            }
        }

        if ($isAlwaysFloat) {
            $node->returnType = new Identifier('float');
            return $node;
        }

        if ($isAlwaysInt) {
            $node->returnType = new Identifier('int');
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }

    private function shouldSkip(ClassMethod|Function_ $functionLike, Scope $scope): bool
    {
        // type is already known, skip
        if ($functionLike->returnType instanceof Node) {
            return true;
        }

        // empty, nothing to ifnd
        if ($functionLike->stmts === null || $functionLike->stmts === []) {
            return true;
        }

        if (! $functionLike instanceof ClassMethod) {
            return false;
        }

        return $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod($functionLike, $scope);
    }
}
