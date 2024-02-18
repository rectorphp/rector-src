<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

final class PhpDocTyped
{
    public function run($item)
    {
        /** @var ?\stdClass $item */

        if (!$item) {
            return 'empty';
        }
    }

    public function go($item)
    {
        /** @var ?\stdClass $item */

        if ($item) {
            return 'not empty';
        }
    }
}

?>
