<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersion;
use Rector\TypeDeclaration\TypeAnalyzer\AlwaysStrictBoolExprAnalyzer;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictReturnExprRector\ReturnTypeFromStrictReturnExprRectorTest
 */
final class ReturnTypeFromStrictReturnExprRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly AlwaysStrictBoolExprAnalyzer $alwaysStrictBoolExprAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add strict return type based on returned strict expr type', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        return $this->first() && $this->somethingElse();
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(): bool
    {
        return $this->first() && $this->somethingElse();
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
        return [ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param ClassMethod|Function_|Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->returnType !== null) {
            return null;
        }

        if (! $this->hasSingleStrictReturn($node)) {
            return null;
        }

        $node->returnType = new Identifier('bool');
        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_70;
    }

    /**
     * @param Return_[] $returns
     */
    private function areExclusiveExprReturns(array $returns): bool
    {
        foreach ($returns as $return) {
            if (! $return->expr instanceof Expr) {
                return false;
            }
        }

        return true;
    }

    private function hasClassMethodRootReturn(ClassMethod|Function_|Closure $functionLike): bool
    {
        foreach ((array) $functionLike->stmts as $stmt) {
            if ($stmt instanceof Return_) {
                return true;
            }
        }

        return false;
    }

    private function hasSingleStrictReturn(ClassMethod|Closure|Function_ $functionLike): bool
    {
        if ($functionLike->stmts === null) {
            return false;
        }

        if ($this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped($functionLike, [Yield_::class])) {
            return false;
        }

        /** @var Return_[] $returns */
        $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($functionLike, Return_::class);
        if ($returns === []) {
            return false;
        }

        // is one statement depth 3?
        if (! $this->areExclusiveExprReturns($returns)) {
            return false;
        }

        // has root return?
        if (! $this->hasClassMethodRootReturn($functionLike)) {
            return false;
        }

        foreach ($returns as $return) {
            // we need exact expr return
            if (! $return->expr instanceof Expr) {
                return false;
            }

            if (! $this->alwaysStrictBoolExprAnalyzer->isStrictBoolExpr($return->expr)) {
                return false;
            }
        }

        return true;
    }
}
