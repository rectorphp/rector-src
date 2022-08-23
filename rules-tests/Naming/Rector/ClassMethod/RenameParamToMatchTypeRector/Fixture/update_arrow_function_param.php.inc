<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Source\SingleSomeClass;

class UpdateArrayFunctionParam
{
    public function run(): void
    {
        $function = static fn (SingleSomeClass $class): bool => true;
        $function(new SingleSomeClass());
    }
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Source\SingleSomeClass;

class UpdateArrayFunctionParam
{
    public function run(): void
    {
        $function = static fn (SingleSomeClass $singleSomeClass): bool => true;
        $function(new SingleSomeClass());
    }
}

?>
