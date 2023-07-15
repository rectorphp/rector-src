<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class CallInTernaryVar
{
    public function getValue()
    {
        $x = $this->get();
        return rand(0,1) ? $x : null;
    }

    public function get(): CallInTernaryVar {}
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class CallInTernaryVar
{
    public function getValue(): ?\Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture\CallInTernaryVar
    {
        $x = $this->get();
        return rand(0,1) ? $x : null;
    }

    public function get(): CallInTernaryVar {}
}

?>
