<?php

namespace Rector\Tests\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector\Fixture;

final class SkipMixed
{
    public function run($array, $k)
    {
        array_key_exists($k, $array);
    }
}
