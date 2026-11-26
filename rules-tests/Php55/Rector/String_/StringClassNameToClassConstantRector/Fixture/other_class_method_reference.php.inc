<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class OtherClassMethodReference
{
    public function run()
    {
        return [
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::run',
            '\Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::run',
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class OtherClassMethodReference
{
    public function run()
    {
        return [
            \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::class . '::run',
            \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::class . '::run',
        ];
    }
}

?>
