<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

use DateTime;

final class DateTimeFormatReturn
{
    function aa(DateTime $dateTime)
    {
        return $dateTime->format('Y-m-d');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

use DateTime;

final class DateTimeFormatReturn
{
    function aa(DateTime $dateTime): string
    {
        return $dateTime->format('Y-m-d');
    }
}

?>
