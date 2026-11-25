<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

trait MethodReferenceInTrait
{
    public function run()
    {
        return [
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MethodReferenceInTrait::run',
            '\Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MethodReferenceInTrait::run',
            'Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MethodReferenceInTrait::' . $this->name(),
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

trait MethodReferenceInTrait
{
    public function run()
    {
        return [
            \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MethodReferenceInTrait::class . '::run',
            \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MethodReferenceInTrait::class . '::run',
            \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture\MethodReferenceInTrait::class . '::' . $this->name(),
        ];
    }
}

?>
