<?php

declare(strict_types=1);

namespace Rector\Php72\Rector\Unset_;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Cast\Unset_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php72\Rector\Unset_\UnsetCastRector\UnsetCastRectorTest
 */
final class UnsetCastRector extends AbstractRector implements MinPhpVersionInterface
{
    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NO_UNSET_CAST;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removes (unset) cast', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$different = (unset) $value;

$value = (unset) $value;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$different = null;

unset($value);
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Unset_::class, Assign::class, Expression::class];
    }

    /**
     * @param Unset_|Assign|Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Assign) {
            return $this->refactorAssign($node);
        }

        if ($node instanceof Expression) {
            if (! $node->expr instanceof Unset_) {
                return null;
            }

            $this->removeNode($node);
            return null;
        }

        return $this->nodeFactory->createNull();
    }

    private function refactorAssign(Assign $assign): ?FuncCall
    {
        if (! $assign->expr instanceof Unset_) {
            return null;
        }

        $unset = $assign->expr;
        if (! $this->nodeComparator->areNodesEqual($assign->var, $unset->expr)) {
            return null;
        }

        return $this->nodeFactory->createFuncCall('unset', [$assign->var]);
    }
}
