<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

final class WithNativeReturnMixed
{
    public function getData()
    {
        return $this->get();
    }

    private function get(): mixed
    {
        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

final class WithNativeReturnMixed
{
    public function getData(): mixed
    {
        return $this->get();
    }

    private function get(): mixed
    {
        return null;
    }
}

?>
