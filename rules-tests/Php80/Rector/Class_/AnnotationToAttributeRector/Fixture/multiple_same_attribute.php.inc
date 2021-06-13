<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixtureAutoImported;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ChangeTrackingPolicy;

/**
 * @Entity
 */
class User
{
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixtureAutoImported;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ChangeTrackingPolicy;

#[\Doctrine\ORM\Mapping\Entity]
class User
{
}
?>
