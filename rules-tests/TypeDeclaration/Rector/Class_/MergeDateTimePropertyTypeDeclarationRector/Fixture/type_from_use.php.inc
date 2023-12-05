<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\MergeDateTimePropertyTypeDeclarationRector\Fixture;

use DateTimeInterface;
use DateTime;

final class TypeFromUse
{
    /**
     * @var DateTimeInterface
     */
    private DateTime $dateTime;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\MergeDateTimePropertyTypeDeclarationRector\Fixture;

use DateTimeInterface;
use DateTime;

final class TypeFromUse
{
    private \DateTimeInterface $dateTime;
}

?>
