<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ORM\Table(name="test", indexes={
 *     @ORM\Index(columns={"created_at"})
 * }))
 * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
 * @ExclusionPolicy("ALL")
 */
class NoStripUnsupportedAnnotations
{
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("ALL")
 */
#[ORM\Table(name: 'test')]
#[ORM\Entity(repositoryClass: 'App\Repository\TestRepository')]
#[ORM\Index(columns: ['created_at'])]
class NoStripUnsupportedAnnotations
{
}
?>
