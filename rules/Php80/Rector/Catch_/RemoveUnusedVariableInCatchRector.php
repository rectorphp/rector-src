<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Catch_;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Catch_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DeadCode\NodeAnalyzer\ExprUsedInNodeAnalyzer;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/non-capturing_catches
 *
 * @see \Rector\Tests\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector\RemoveUnusedVariableInCatchRectorTest
 */
final class RemoveUnusedVariableInCatchRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ExprUsedInNodeAnalyzer $exprUsedInNodeAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused variable in catch()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        try {
        } catch (Throwable $notUsedThrowable) {
        }
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        try {
        } catch (Throwable) {
        }
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
        return [Catch_::class];
    }

    /**
     * @param Catch_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $caughtVar = $node->var;
        if (! $caughtVar instanceof Variable) {
            return null;
        }

        if ($this->isVariableUsedInStmts($node->stmts, $caughtVar)) {
            return null;
        }

        if ($this->isVariableUsedNext($node, $caughtVar)) {
            return null;
        }

        $node->var = null;

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NON_CAPTURING_CATCH;
    }

    /**
     * @param Node[] $nodes
     */
    private function isVariableUsedInStmts(array $nodes, Variable $variable): bool
    {
        return (bool) $this->betterNodeFinder->findFirst(
            $nodes,
            fn (Node $node): bool => $this->exprUsedInNodeAnalyzer->isUsed($node, $variable)
        );
    }

    private function isVariableUsedNext(Catch_ $catch, Variable $variable): bool
    {
        return (bool) $this->betterNodeFinder->findFirstNext(
            $catch,
            fn (Node $node): bool => $this->exprUsedInNodeAnalyzer->isUsed($node, $variable)
        );
    }
}
