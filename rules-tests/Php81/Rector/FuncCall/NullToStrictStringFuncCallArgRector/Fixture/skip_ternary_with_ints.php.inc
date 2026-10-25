<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class TernaryWithInts
{
    public function run($value)
    {
        explode(
            ',',
            (string) $value ? 100 : 500
        );
    }
}

?>
