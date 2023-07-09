<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Catch_;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\TryCatch;
use PHPStan\Analyser\Scope;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/non-capturing_catches
 *
 * @see \Rector\Tests\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector\RemoveUnusedVariableInCatchRectorTest
 */
final class RemoveUnusedVariableInCatchRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
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
        return [StmtsAwareInterface::class];
    }

    /**
     * @param StmtsAwareInterface $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof TryCatch) {
                continue;
            }

            foreach ($stmt->catches as $catch) {
                $caughtVar = $catch->var;
                if (! $caughtVar instanceof Variable) {
                    continue;
                }

                /** @var string $variableName */
                $variableName = $this->getName($caughtVar);

                $isVariableUsed = (bool) $this->betterNodeFinder->findVariableOfName($catch->stmts, $variableName);
                if ($isVariableUsed) {
                    continue;
                }

                if ($this->isUsedInNextStmt($node, $key + 1, $variableName)) {
                    continue;
                }

                $catch->var = null;
            }
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NON_CAPTURING_CATCH;
    }

    private function isUsedInNextStmt(StmtsAwareInterface $stmtsAware, int $jumpToKey, string $variableName): bool
    {
        if ($stmtsAware->stmts === null) {
            return false;
        }

        $totalKeys = array_key_last($stmtsAware->stmts);
        for ($key = $jumpToKey; $key <= $totalKeys; ++$key) {
            $isVariableUsed = (bool) $this->betterNodeFinder->findVariableOfName(
                $stmtsAware->stmts[$key],
                $variableName
            );
            if ($isVariableUsed) {
                return true;
            }
        }

        return false;
    }
}
