<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

final class SkipCollection
{
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User\UserToAffiliate")
     */
    private $items = [];
}
