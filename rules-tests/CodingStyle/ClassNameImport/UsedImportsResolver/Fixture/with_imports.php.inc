<?php

namespace Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Fixture;

use Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\FirstType;
use Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\SecondType as AliasedType;
use function Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\someFunction;
use const Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\SOME_CONSTANT;

final class WithImports
{
    public function run(FirstType $firstType, AliasedType $aliasedType): void
    {
        someFunction();
        echo SOME_CONSTANT;
    }
}
