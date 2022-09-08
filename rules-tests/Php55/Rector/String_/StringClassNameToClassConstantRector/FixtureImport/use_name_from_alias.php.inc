<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\FixtureImport;

use Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass as SomeAlias;

class UseNameFromAlias
{
    public function run()
    {
        return 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\FixtureImport;

use Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass as SomeAlias;

class UseNameFromAlias
{
    public function run()
    {
        return SomeAlias::class;
    }
}

?>
