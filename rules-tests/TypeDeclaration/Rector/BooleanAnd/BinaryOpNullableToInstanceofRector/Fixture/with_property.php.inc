<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

final class WithProperty
{
    private ?SomeInstance $someClass;

    public function run()
    {
        if ($this->someClass && $this->someClass->someMethod()) {
            return 'yes';
        }

        return 'no';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance;

final class WithProperty
{
    private ?SomeInstance $someClass;

    public function run()
    {
        if ($this->someClass instanceof \Rector\Tests\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector\Source\SomeInstance && $this->someClass->someMethod()) {
            return 'yes';
        }

        return 'no';
    }
}

?>
