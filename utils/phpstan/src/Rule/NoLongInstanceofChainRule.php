<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * A single boolean chain made of many instanceof checks is a type dispatch in disguise.
 * Past a handful of branches it is slow and hard to read; extract it to a helper method,
 * a match, or a keyed lookup instead.
 *
 * @implements Rule<BinaryOp>
 * @see \Rector\Utils\PHPStan\Tests\Rule\NoLongInstanceofChainRule\NoLongInstanceofChainRuleTest
 */
final readonly class NoLongInstanceofChainRule implements Rule
{
    private const int MAX_INSTANCEOF_COUNT = 7;

    private const string ERROR_MESSAGE = 'Boolean chain has %d instanceof checks, more than the allowed %d. Extract it to a helper method, a match(), or a keyed lookup.';

    private NodeFinder $nodeFinder;

    public function __construct()
    {
        $this->nodeFinder = new NodeFinder();
    }

    public function getNodeType(): string
    {
        return BinaryOp::class;
    }

    /**
     * @param BinaryOp $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->isBooleanBinaryOp($node)) {
            return [];
        }

        $instanceofCount = $this->countInstanceof($node);
        if ($instanceofCount <= self::MAX_INSTANCEOF_COUNT) {
            return [];
        }

        // report a chain once: if an inner boolean operand already exceeds the limit, that inner
        // node reports it instead (nodes are visited inner-first, so avoid the outer duplicate)
        foreach ([$node->left, $node->right] as $operand) {
            if ($operand instanceof BinaryOp
                && $this->isBooleanBinaryOp($operand)
                && $this->countInstanceof($operand) > self::MAX_INSTANCEOF_COUNT) {
                return [];
            }
        }

        $errorMessage = sprintf(self::ERROR_MESSAGE, $instanceofCount, self::MAX_INSTANCEOF_COUNT);

        return [
            RuleErrorBuilder::message($errorMessage)
                ->identifier('rector.longInstanceofChain')
                ->line($node->getStartLine())
                ->build(),
        ];
    }

    private function countInstanceof(BinaryOp $binaryOp): int
    {
        return count($this->nodeFinder->findInstanceOf([$binaryOp], Instanceof_::class));
    }

    private function isBooleanBinaryOp(BinaryOp $binaryOp): bool
    {
        return $binaryOp instanceof BooleanOr
            || $binaryOp instanceof BooleanAnd
            || $binaryOp instanceof LogicalOr
            || $binaryOp instanceof LogicalAnd;
    }
}
