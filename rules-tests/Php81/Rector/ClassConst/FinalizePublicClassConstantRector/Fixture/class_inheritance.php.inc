<?php

namespace Rector\Tests\Php81\Rector\ClassConst\FinalizePublicClassConstantRector\Fixture;

abstract class ParentClass
{
    public const TEST_CONSTANT = 'value1';
}

namespace Rector\Tests\Php81\Rector\ClassConst\FinalizePublicClassConstantRector\Fixture;

class ChildClass extends ParentClass
{
    public const TEST_CONSTANT = 'value2';
}
?>
