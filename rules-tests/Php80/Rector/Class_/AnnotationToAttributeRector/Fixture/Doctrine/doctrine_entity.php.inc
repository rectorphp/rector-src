<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

/**
 * @\Doctrine\ORM\Mapping\Entity(repositoryClass="App\Some\Class")
 */
class DoctrineEntity
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

#[\Doctrine\ORM\Mapping\Entity(repositoryClass: \App\Some\Class::class)]
class DoctrineEntity
{
}

?>
