<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipCryptOneArg
{
    public function run($subject)
    {
        crypt($subject);
    }
}
