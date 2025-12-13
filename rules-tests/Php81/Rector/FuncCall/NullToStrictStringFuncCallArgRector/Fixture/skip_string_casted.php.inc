<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipStringCasted
{
    public function run($value)
    {
        explode(
            ',',
            (string) $value ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''
        );
    }
}
