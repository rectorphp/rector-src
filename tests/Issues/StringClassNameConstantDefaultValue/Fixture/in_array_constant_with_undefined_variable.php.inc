<?php

namespace Rector\Core\Tests\Issues\StringClassNameConstantDefaultValue\Fixture;

final class InArrayConstantWithUndefinedVariable
{
    const SKIP_TYPES = [
       'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\SomeUser'
    ];

    public function run()
    {
        echo $undefinedVariableHere;
     }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\StringClassNameConstantDefaultValue\Fixture;

final class InArrayConstantWithUndefinedVariable
{
    const SKIP_TYPES = [
       'Rector\Config\RectorConfig'
    ];

    public function run()
    {
        $undefinedVariableHere = null;
        echo $undefinedVariableHere;
     }
}

?>
