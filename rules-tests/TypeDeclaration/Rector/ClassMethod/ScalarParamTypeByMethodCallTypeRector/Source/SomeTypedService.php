<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Source;

final class SomeTypedService
{
    public function run(string $name)
    {
    }

    public function runInt(int $count)
    {
    }

    public static function fun($surname, string $name)
    {
    }

    public static function funInt($surname, int $count)
    {
    }

    public function withDefaultNullUnion(bool|string $name = null)
    {
    }
}
