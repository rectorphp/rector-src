<?php

namespace Rector\Tests\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector\Fixture;

final class SkipUnsettedPropertyFetch {
    private $array = ['foo' => 'bar'];

    public function run() {
        unset($this->array);
    }
}
