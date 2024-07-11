<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class SkipNamedArg
{
    public function run(): void
    {
        $this->method(var1: new \DateTime(), var3: ['1']);
        $this->method(var1: new \DateTime(), var2: true);
    }

    private function method(
        ?\DateTime    $var1,
        ?bool         $var2 = false,
        array         $var3 = [],
    ): void
    {
        return [$var1, $var2, $var2];
    }
}
