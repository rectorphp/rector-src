<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\MergeDateTimePropertyTypeDeclarationRector\Fixture;

final class DoNotRemoveDocWithDescription
{
    /**
     * @var \DateTimeInterface some desc
     */
    private \DateTime $dateTime;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\MergeDateTimePropertyTypeDeclarationRector\Fixture;

final class DoNotRemoveDocWithDescription
{
    /**
     * @var \DateTimeInterface some desc
     */
    private \DateTimeInterface $dateTime;
}

?>
