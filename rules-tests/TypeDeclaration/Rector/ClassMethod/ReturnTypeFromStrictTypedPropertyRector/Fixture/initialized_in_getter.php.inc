<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector\Fixture;

use stdClass;

final class InitializedInGetter
{
    private ?stdClass $demo = null;

    public function getDemo() {
        if ($this->demo === null) {
            $this->demo = new stdClass();
        }
        return $this->demo;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector\Fixture;

use stdClass;

final class InitializedInGetter
{
    private ?stdClass $demo = null;

    public function getDemo(): \stdClass {
        if ($this->demo === null) {
            $this->demo = new stdClass();
        }
        return $this->demo;
    }
}

?>
