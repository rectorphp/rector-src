<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SkipAnonymousClass
{
    private $number;

    public function setValue($number)
    {
        $someClass = new class {
            private string $number;
            public function setItem($number)
            {
                $this->number = $number;
            }
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SkipAnonymousClass
{
    private $number;

    public function setValue($number)
    {
        $someClass = new class {
            private string $number;
            public function setItem(string $number)
            {
                $this->number = $number;
            }
        };
    }
}

?>
