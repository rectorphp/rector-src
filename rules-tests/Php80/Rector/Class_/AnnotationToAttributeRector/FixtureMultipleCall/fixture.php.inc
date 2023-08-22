<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixtureMultipleCall;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @Entity
 */
class User
{
    /**
     * @ManyToMany(targetEntity="Phonenumber")
     */
    public $phonenumbers;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixtureMultipleCall;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;

#[Entity]
class User
{
    #[ManyToMany(targetEntity: 'Phonenumber')]
    public $phonenumbers;
}

?>