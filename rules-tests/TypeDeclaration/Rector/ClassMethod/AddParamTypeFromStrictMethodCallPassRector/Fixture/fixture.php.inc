<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromStrictMethodCallPassRector\Fixture;

final class SomeClass
{
    public function setValue($number)
    {
        $this->checkInt($number);
    }

    private function checkInt(int $integer)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromStrictMethodCallPassRector\Fixture;

final class SomeClass
{
    public function setValue(int $number)
    {
        $this->checkInt($number);
    }

    private function checkInt(int $integer)
    {
    }
}

?>
