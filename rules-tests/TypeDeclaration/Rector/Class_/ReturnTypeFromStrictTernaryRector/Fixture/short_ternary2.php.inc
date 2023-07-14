<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class ShortTernary2
{
    public function getValue()
    {
        $x = $this->get();
        return rand(0,1) ? $x : null;
    }

    public function get(): ShortTernary2 {}
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class ShortTernary2
{
    public function getValue(): ?\Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture\ShortTernary2
    {
        $x = $this->get();
        return rand(0,1) ? $x : null;
    }

    public function get(): ShortTernary2 {}
}

?>
