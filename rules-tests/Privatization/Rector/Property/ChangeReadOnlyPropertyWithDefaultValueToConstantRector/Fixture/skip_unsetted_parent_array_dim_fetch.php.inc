<?php

namespace Rector\Tests\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector\Fixture;

final class SkipUnsettedParentArrayDimFetch {
    private static $array = ['foo' => 'bar'];

    public function run() {
        unset(self::$array['foo']);
    }
}
