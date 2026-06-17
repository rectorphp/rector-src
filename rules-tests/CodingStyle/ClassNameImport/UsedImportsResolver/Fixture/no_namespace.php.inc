<?php

use Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\FirstType;
use function Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\someFunction;

final class NoNamespace
{
    public function run(FirstType $firstType): void
    {
        someFunction();
    }
}
