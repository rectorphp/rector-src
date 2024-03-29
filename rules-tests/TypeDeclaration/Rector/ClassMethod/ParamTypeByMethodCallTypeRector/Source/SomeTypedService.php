<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Source;

final class SomeTypedService
{
    public function run(string $name)
    {
    }

    public static function fun($surname, string $name)
    {
    }

    public function withDefaultNull(string $name = null)
    {
    }

    public function withDefaultNullUnion(bool|string $name = null)
    {
    }
}
