<?php

namespace Rector\Tests\Php82\Rector\Param\AddSensitiveParameterAttributeRector\Fixture;

class SkipSensitiveParameterAttributeExists
{
    public function login($username, #[\SensitiveParameter]
    $password): void
    {
    }
}

?>
