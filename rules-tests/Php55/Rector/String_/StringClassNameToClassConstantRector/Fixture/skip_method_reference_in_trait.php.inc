<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

trait SkipMethodReferenceInTrait
{
    public function run()
    {
        return [
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipMethodReferenceInTrait::run',
            '\Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipMethodReferenceInTrait::run',
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\SkipMethodReferenceInTrait::' . $this->name(),
        ];
    }
}

?>
