<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class SkipVariadicParam
{
    public function run(): void
    {
        $this->createQueryBuilder('first', 'second');
    }

    private function createQueryBuilder(...$returnValues): void
    {
    }
}
