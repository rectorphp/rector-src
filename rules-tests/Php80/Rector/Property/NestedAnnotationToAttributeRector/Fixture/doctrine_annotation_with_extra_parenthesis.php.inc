<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="changelog",
 *   indexes={
 *     @ORM\Index(name="entity", columns={"entity_class", "entity_id"}),
 *   }
 * ))
 * @ORM\Entity(repositoryClass="ChangeLogRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ChangeLog
{
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;
 
use Doctrine\ORM\Mapping as ORM;
 
#[ORM\Table(name: 'changelog')]
#[ORM\Index(name: 'entity', columns: ['entity_class', 'entity_id'])]
#[ORM\Entity(repositoryClass: ChangeLogRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ChangeLog
{
}
?>
