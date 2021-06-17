<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

class DoctrineGuid
{
    /**
     * @ORM\Column(type="guid")
     * @var string
     */
    private $guid;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

class DoctrineGuid
{
    /**
     * @ORM\Column(type="guid")
     */
    private string $guid;
}

?>
