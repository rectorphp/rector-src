<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class ShortTernary
{
    public function getValue()
    {
        $x = $this->get();
        return rand(0,1) ? $x : null;
    }

    public function get(): ShortTernary {}
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class ShortTernary
{
    public function getValue(): ?\Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture\ShortTernary
    {
        $x = $this->get();
        return rand(0,1) ? $x : null;
    }

    public function get(): ShortTernary {}
}

?>
