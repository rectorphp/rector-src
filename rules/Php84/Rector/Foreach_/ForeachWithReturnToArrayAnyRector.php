<?php

declare(strict_types=1);

namespace Rector\Php84\Rector\Foreach_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Php84\NodeAnalyzer\ForeachKeyUsedInConditionalAnalyzer;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php84\Rector\Foreach_\ForeachWithReturnToArrayAnyRector\ForeachWithReturnToArrayAnyRectorTest
 */
final class ForeachWithReturnToArrayAnyRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ValueResolver $valueResolver,
        private readonly ForeachKeyUsedInConditionalAnalyzer $foreachKeyUsedInConditionalAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace foreach with early return with array_any',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
foreach ($animals as $animal) {
    if (str_starts_with($animal, 'c')) {
        return true;
    }
}
return false;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
return array_any($animals, fn($animal) => str_starts_with($animal, 'c'));
CODE_SAMPLE
                ),
            ]
        );
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
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof Foreach_) {
                continue;
            }

            $foreach = $stmt;
            $nextStmt = $node->stmts[$key + 1] ?? null;

            if (! $nextStmt instanceof Return_) {
                continue;
            }

            if (! $nextStmt->expr instanceof Expr) {
                continue;
            }

            if (! $this->valueResolver->isFalse($nextStmt->expr)) {
                continue;
            }

            if (! $this->isValidForeachStructure($foreach)) {
                continue;
            }

            /** @var If_ $firstNodeInsideForeach */
            $firstNodeInsideForeach = $foreach->stmts[0];
            $condition = $firstNodeInsideForeach->cond;

            $params = [];

            if ($foreach->valueVar instanceof Variable) {
                $params[] = new Param($foreach->valueVar);
            }

            if (
                $foreach->keyVar instanceof Variable &&
                $this->foreachKeyUsedInConditionalAnalyzer->isUsed($foreach->keyVar, $condition)
            ) {
                $params[] = new Param(new Variable((string) $this->getName($foreach->keyVar)));
            }

            $arrowFunction = new ArrowFunction([
                'params' => $params,
                'expr' => $condition,
            ]);

            $funcCall = $this->nodeFactory->createFuncCall('array_any', [$foreach->expr, $arrowFunction]);

            $node->stmts[$key] = new Return_($funcCall);
            unset($node->stmts[$key + 1]);
            $node->stmts = array_values($node->stmts);

            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ARRAY_FIND;
    }

    private function isValidForeachStructure(Foreach_ $foreach): bool
    {
        if (count($foreach->stmts) !== 1) {
            return false;
        }

        if (! $foreach->stmts[0] instanceof If_) {
            return false;
        }

        $ifStmt = $foreach->stmts[0];

        if (count($ifStmt->stmts) !== 1) {
            return false;
        }

        if (! $ifStmt->stmts[0] instanceof Return_) {
            return false;
        }

        $returnStmt = $ifStmt->stmts[0];

        if (! $returnStmt->expr instanceof Expr) {
            return false;
        }

        if (! $this->valueResolver->isTrue($returnStmt->expr)) {
            return false;
        }

        $type = $this->nodeTypeResolver->getNativeType($foreach->expr);

        return $type->isArray()
            ->yes();
    }
}
