<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

/**
 * @api used
 * @see somewhere
 *
 * @\Doctrine\ORM\Mapping\Entity(repositoryClass="App\Some\Class")
 */
class HandleWithApiAbove
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

/**
 * @api used
 * @see somewhere
 */
#[\Doctrine\ORM\Mapping\Entity(repositoryClass: \App\Some\Class::class)]
class HandleWithApiAbove
{
}

?>
