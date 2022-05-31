<?php

namespace Rector\Core\Tests\Issues\DoNotReplaceUnknownTargetEntities\Fixture;

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
