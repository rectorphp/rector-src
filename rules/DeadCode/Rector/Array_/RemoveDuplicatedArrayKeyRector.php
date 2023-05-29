<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\Array_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PreInc;
use Rector\Core\Contract\PhpParser\NodePrinterInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Util\MultiInstanceofChecker;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://3v4l.org/SG0Wu
 * @see \Rector\Tests\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector\RemoveDuplicatedArrayKeyRectorTest
 */
final class RemoveDuplicatedArrayKeyRector extends AbstractRector
{
    /**
     * @var array<class-string<Expr>>
     */
    private const ALLOWED_KEY_DUPLICATES = [PreInc::class, PreDec::class];

    public function __construct(
        private readonly NodePrinterInterface $nodePrinter,
        private readonly MultiInstanceofChecker $multiInstanceofChecker
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove duplicated key in defined arrays.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$item = [
    1 => 'A',
    1 => 'B'
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$item = [
    1 => 'B'
];
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Array_::class];
    }

    /**
     * @param Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $duplicatedKeysArrayItems = $this->resolveDuplicateKeysArrayItems($node);
        if ($duplicatedKeysArrayItems === []) {
            return null;
        }

        foreach ($node->items as $key => $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                continue;
            }

            if (! $this->isArrayItemDuplicated($duplicatedKeysArrayItems, $arrayItem)) {
                continue;
            }

            unset($node->items[$key]);
        }

        return $node;
    }

    /**
     * @return ArrayItem[]
     */
    private function resolveDuplicateKeysArrayItems(Array_ $array): array
    {
        $arrayItemsByKeys = [];

        foreach ($array->items as $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                continue;
            }

            if (! $arrayItem->key instanceof Expr) {
                continue;
            }

            $keyValue = $this->nodePrinter->print($arrayItem->key);
            $arrayItemsByKeys[$keyValue][] = $arrayItem;
        }

        return $this->filterItemsWithSameKey($arrayItemsByKeys);
    }

    /**
     * @param array<mixed, ArrayItem[]> $arrayItemsByKeys
     * @return array<ArrayItem>
     */
    private function filterItemsWithSameKey(array $arrayItemsByKeys): array
    {
        $duplicatedArrayItems = [];

        foreach ($arrayItemsByKeys as $arrayItems) {
            if (count($arrayItems) <= 1) {
                continue;
            }

            $currentArrayItem = current($arrayItems);

            /** @var Expr $currentArrayItemKey */
            $currentArrayItemKey = $currentArrayItem->key;

            if ($this->multiInstanceofChecker->isInstanceOf($currentArrayItemKey, self::ALLOWED_KEY_DUPLICATES)) {
                continue;
            }

            // keep last one
            array_pop($arrayItems);

            $duplicatedArrayItems = array_merge($duplicatedArrayItems, $arrayItems);
        }

        return $duplicatedArrayItems;
    }

    /**
     * @param ArrayItem[] $duplicatedKeysArrayItems
     */
    private function isArrayItemDuplicated(array $duplicatedKeysArrayItems, ArrayItem $arrayItem): bool
    {
        return in_array($arrayItem, $duplicatedKeysArrayItems, true);
    }
}
