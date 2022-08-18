<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name=App\Entity\Aktenzeichen::TABLE_NAME, uniqueConstraints={@ORM\UniqueConstraint(name="aktenzeichen_idx", columns={"aktenzeichen"})})
 */
class StringAnnotationValue
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: App\Entity\Aktenzeichen::TABLE_NAME)]
#[ORM\UniqueConstraint(name: 'aktenzeichen_idx', columns: ['aktenzeichen'])]
class StringAnnotationValue
{
}

?>
