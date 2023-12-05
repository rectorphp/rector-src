<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\MergeDateTimePropertyTypeDeclarationRector\Fixture;

final class SomeClass
{
    /**
     * @var \DateTimeInterface
     */
    private \DateTime $dateTime;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\MergeDateTimePropertyTypeDeclarationRector\Fixture;

final class SomeClass
{
    private \DateTime $dateTime;
}

?>
