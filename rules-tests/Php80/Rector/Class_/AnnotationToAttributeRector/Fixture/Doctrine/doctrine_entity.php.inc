<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

/**
 * @\Doctrine\ORM\Mapping\Embeddable
 */
class PreserveIntKeyDefined
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

#[\Doctrine\ORM\Mapping\Embeddable]
class PreserveIntKeyDefined
{
}

?>
