<?php

namespace Rector\Core\Tests\Issues\Issue7023\Fixture\Php73;

use Doctrine\ORM\Mapping as ORM;
use Rector\Core\Tests\Issues\Issue7023\Source\SomeUser;

/**
 * @ORM\Entity
 */
class Fixture
{

    /**
     * @ORM\ManyToOne(targetEntity="\Rector\Core\Tests\Issues\Issue7023\Source\SomeUser", inversedBy="images")
     * @ORM\JoinColumn(name="data_id", nullable=true)
     * @var SomeUser
     */
    private $user;
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\Issue7023\Fixture\Php73;

use Doctrine\ORM\Mapping as ORM;
use Acme\Entity\User;

/**
 * @ORM\Entity
 */
class Fixture
{

    /**
     * @ORM\ManyToOne(targetEntity="\Rector\Core\Tests\Issues\Issue7023\Source\SomeUser", inversedBy="images")
     * @ORM\JoinColumn(name="data_id", nullable=true)
     * @var \Rector\Core\Tests\Issues\Issue7023\Source\SomeUser|null
     */
    private $user;
}

?>
