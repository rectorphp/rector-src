<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

trait SkipClassStringOnTrait
{
    public function run(string $param)
    {
        return str_replace($param, '', $this::class);
    }
}