<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Source\FooBar;

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
