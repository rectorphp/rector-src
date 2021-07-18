<?php
namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayParamDocTypeRector\Fixture;

class SkipNullableTyped
{
    public bool $bar;

    public function setBar(?bool $bar): void
    {
        $this->bar = $bar;
    }
}
?>
