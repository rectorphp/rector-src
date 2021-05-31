<?php

declare(strict_types=1);

namespace Rector\DowngradePhp73\Rector\FuncCall;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/is-countable
 *
 * @see \Rector\Tests\DowngradePhp73\Rector\FuncCall\DowngradeIsCountableRector\DowngradeIsCountableRectorTest
 */
final class DowngradeIsCountableRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Downgrade is_countable() to former version', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$items = [];
return is_countable($items);
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
$items = [];
return is_array($items) || $items instanceof Countable;
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Expr\FuncCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'is_countable')) {
            return null;
        }

        $isArrayFuncCall = $this->nodeFactory->createFuncCall('is_array', $node->args);
        $instanceofCountable = new Node\Expr\Instanceof_($node->args[0]->value, new Node\Name\FullyQualified(
            'Countable'
        ));

        return new Node\Expr\BinaryOp\BooleanOr($isArrayFuncCall, $instanceofCountable);
    }
}
