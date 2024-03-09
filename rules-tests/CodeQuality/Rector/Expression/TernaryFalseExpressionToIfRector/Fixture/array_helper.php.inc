<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector\Fixture;

class ArrayHelper
{
    public static function mksort(array &$array): void
    {
        $keys = array_keys($array);
        $isIndexedArray = array_reduce($keys, static fn ($result, $item): bool => $result && is_numeric($item), true);
        $isIndexedArray
            ? sort($array)
            : ksort($array);

        foreach ($array as &$value) {
            if (is_array($value)) {
                self::mksort($value);
            }
        }
    }
}
?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector\Fixture;

// what is expected code?
// should remain the same? delete part below ----- (included)

?>
