<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SkipMethodReferenceConcat
{
    public function run(string $func)
    {
        return [
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Source\AnotherClass::' . $func,
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipMethodReferenceConcat::run::' . $func,
            $func . 'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipMethodReferenceConcat::',
        ];
    }
}

?>
