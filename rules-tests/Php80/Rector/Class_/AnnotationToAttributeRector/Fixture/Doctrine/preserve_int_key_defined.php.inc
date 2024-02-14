<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * @\Doctrine\ORM\Mapping\Embeddable
 * @ORM\DiscriminatorMap({ 1 = "CostDetailEntity" })
 */
class PreserveIntKeyDefined
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[\Doctrine\ORM\Mapping\Embeddable]
#[ORM\DiscriminatorMap([1 => 'CostDetailEntity'])]
class PreserveIntKeyDefined
{
}

?>
