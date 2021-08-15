<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

use stdClass;
use DateTime;

class ClassStringArray
{
    public function getData()
    {
        return [
            stdClass::class,
            DateTime::class,
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

use stdClass;
use DateTime;

class ClassStringArray
{
    /**
     * @return class-string[]
     */
    public function getData()
    {
        return [
            stdClass::class,
            DateTime::class,
        ];
    }
}

?>