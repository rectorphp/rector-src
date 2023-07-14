<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class ShortTernary
{
    public function getValue()
    {
        $x = false;
        if (rand(0,1)) {
            $x = $this->get();
        }
        return $x ?: null;
    }

    public function get(): ShortTernary {}
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class ShortTernary
{
    public function getValue(): ?\Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture\ShortTernary
    {
        $x = false;
        if (rand(0,1)) {
            $x = $this->get();
        }
        return $x ?: null;
    }

    public function get(): ShortTernary {}
}

?>
