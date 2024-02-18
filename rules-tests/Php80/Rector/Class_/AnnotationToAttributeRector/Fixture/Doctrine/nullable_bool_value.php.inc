<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Some\Class")
 */
class NullableBoolValue
{
    /**
     * @ORM\Column(type="bigint", nullable="true")
     */
    private int $stop_ts;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Some\Class::class)]
class NullableBoolValue
{
    #[ORM\Column(type: 'bigint', nullable: true)]
    private int $stop_ts;
}

?>
