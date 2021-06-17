<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DoctrineBoolean
{
    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $bool;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class DoctrineBoolean
{
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $bool;
}

?>
