<?php

declare(strict_types=1);

namespace Rector\DowngradePhp81\Rector\Array_;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PHPStan\Type\ArrayType;
use PHPStan\Type\IntegerType;
use Rector\DowngradePhp74\Rector\Array_\DowngradeArraySpreadRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/array_unpacking_string_keys
 * @see \Rector\Tests\DowngradePhp81\Rector\Array_\DowngradeArraySpreadStringKeyRector\DowngradeArraySpreadStringKeyRectorTest
 */
final class DowngradeArraySpreadStringKeyRector extends DowngradeArraySpreadRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace array spread with string key to array_merge function',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $parts = ['a' => 'b'];
        $parts2 = ['c' => 'd'];

        $result = [...$parts, ...$parts2];
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $parts = ['a' => 'b'];
        $parts2 = ['c' => 'd'];

        $result = array_merge($parts, $parts2);
    }
}
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
        return [Array_::class];
    }

    /**
     * @param Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        return parent::refactor($node);
    }

    private function shouldSkip(Array_ $array): bool
    {
        foreach ($array->items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if (! $item->unpack) {
                continue;
            }

            $type = $this->nodeTypeResolver->getType($item->value);
            if (! $type instanceof ArrayType) {
                continue;
            }

            $keyType = $type->getKeyType();
            if ($keyType instanceof IntegerType) {
                return true;
            }
        }

        return false;
    }
}
