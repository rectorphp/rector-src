<?php
namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayParamDocTypeRector\Fixture;

class Foo
{
    public bool $bar;

    public function setBar(?bool $bar): void
    {
        $this->bar = $bar;
    }
}
?>
