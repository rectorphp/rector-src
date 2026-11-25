<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class OtherClassMethodReferenceConcat
{
    public function run(string $func)
    {
        return [
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::' . $func,
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OtherClassMethodReferenceConcat::run::' . $func,
            $func . 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OtherClassMethodReferenceConcat::',
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class OtherClassMethodReferenceConcat
{
    public function run(string $func)
    {
        return [
            \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::class . '::' . $func,
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OtherClassMethodReferenceConcat::run::' . $func,
            $func . 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\OtherClassMethodReferenceConcat::',
        ];
    }
}

?>
