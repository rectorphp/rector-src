<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture\ArrowFunction;

use Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Source\FooBar;

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
