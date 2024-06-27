<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;

/**
 * @ORM\Entity
 */
class DoctrineEntityProperty
{
    private $unusedProperty;

    /**
     * @ORM\Column
     */
    private $unusedAnnotatedProperty;

    #[Id, Column(type: "integer")]
    private $id = null;
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;

/**
 * @ORM\Entity
 */
class DoctrineEntityProperty
{
    /**
     * @ORM\Column
     */
    private $unusedAnnotatedProperty;

    #[Id, Column(type: "integer")]
    private $id = null;
}

?>
