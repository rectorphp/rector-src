<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture\ArrowFunction;

use Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Source\FooBar;

final class SkipNoArgsPassed
{
    public function run()
    {
        $data = [
            'callback' => fn ($foo) => FooBar::optionalArgs()
        ];

        return $data;
    }
}
