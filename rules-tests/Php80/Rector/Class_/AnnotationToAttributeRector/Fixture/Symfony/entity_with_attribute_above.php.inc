<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Doctrine\ORM\Mapping as ORM;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Annotation\OpenApi\PastAnnotation;

#[ORM\Entity]
/**
 * @PastAnnotation
 */
final class EntityWithAttributeAbove
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Doctrine\ORM\Mapping as ORM;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Annotation\OpenApi\FutureAttribute;

#[ORM\Entity]
#[FutureAttribute]
final class EntityWithAttributeAbove
{
}

?>
