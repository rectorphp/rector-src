<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SkipOtherClassMethodReference
{
    public function run()
    {
        return [
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::run',
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipOtherClassMethodReference::',
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipOtherClassMethodReference::run::twice',
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipOtherClassMethodReference::$property',
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\skipotherclassmethodreference::run',
        ];
    }
}

?>
