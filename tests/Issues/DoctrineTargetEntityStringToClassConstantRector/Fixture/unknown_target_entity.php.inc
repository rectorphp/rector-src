<?php

namespace Rector\Core\Tests\Issues\DoctrineTargetEntityStringToClassConstantRector\Fixture;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use My\Namespace\UsedUnknownClass;

final class MyEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="UsedUnknownClass")
     */
    private readonly ?Collection $items;
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\DoctrineTargetEntityStringToClassConstantRector\Fixture;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use My\Namespace\UsedUnknownClass;

final class MyEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="UsedUnknownClass")
     */
    private readonly ?Collection $items;
}

?>
