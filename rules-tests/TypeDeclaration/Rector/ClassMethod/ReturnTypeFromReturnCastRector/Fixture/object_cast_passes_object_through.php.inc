<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnCastRector\Fixture;

final class ObjectCastPassesObjectThrough
{
    public function run(\DateTime $dateTime)
    {
        return (object) $dateTime;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnCastRector\Fixture;

final class ObjectCastPassesObjectThrough
{
    public function run(\DateTime $dateTime): object
    {
        return (object) $dateTime;
    }
}

?>
