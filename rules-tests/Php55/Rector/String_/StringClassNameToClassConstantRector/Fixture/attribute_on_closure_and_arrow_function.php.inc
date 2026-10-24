<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class AttributeOnClosureAndArrowFunction
{
    public function run()
    {
        $closure = #[\Closure(class: 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass')] function () {
        };

        return new \stdClass(#[\Closure(class: 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass')] fn () => 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class AttributeOnClosureAndArrowFunction
{
    public function run()
    {
        $closure = #[\Closure(class: \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::class)] function () {
        };

        return new \stdClass(#[\Closure(class: \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::class)] fn () => 1);
    }
}

?>
