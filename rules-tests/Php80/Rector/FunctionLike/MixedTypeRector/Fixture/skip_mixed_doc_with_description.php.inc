<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

class SkipMixedDocWithDescription
{
    /**
     * @param mixed $value Value for dump
     */
    public function test(mixed $value): void
    {
        var_dump($value);
    }
}
