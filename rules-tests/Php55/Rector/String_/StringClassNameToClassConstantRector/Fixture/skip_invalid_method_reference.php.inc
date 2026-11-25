<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

final class SkipInvalidMethodReference
{
    public function run()
    {
        return [
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipInvalidMethodReference::',
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipInvalidMethodReference::run::twice',
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipInvalidMethodReference::$property',
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MissingClass::run',
            'lowercase\SkipInvalidMethodReference::run',
        ];
    }
}

?>
