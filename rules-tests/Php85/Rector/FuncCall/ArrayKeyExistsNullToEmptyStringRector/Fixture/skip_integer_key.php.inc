<?php

namespace Rector\Tests\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector\Fixture;

final class SkipIntegerKey
{
    public function run($array)
    {
        array_key_exists(0, $array);
    }
}
