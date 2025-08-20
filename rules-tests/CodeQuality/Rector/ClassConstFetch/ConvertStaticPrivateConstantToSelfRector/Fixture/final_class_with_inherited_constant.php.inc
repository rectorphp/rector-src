<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Source\ParentClass;

final class FinalClassWithInheritedConstant extends ParentClass
{
    public function run(): void
    {
        return static::FAILURE;
        return static::SUCCESS;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Source\ParentClass;

final class FinalClassWithInheritedConstant extends ParentClass
{
    public function run(): void
    {
        return self::FAILURE;
        return self::SUCCESS;
    }
}

?>
