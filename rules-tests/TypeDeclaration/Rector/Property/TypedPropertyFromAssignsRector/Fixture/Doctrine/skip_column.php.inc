<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

final class SkipColumn
{
    /**
     * @ORM\Column(name="userID", type="integer", nullable=false)
     * @ORM\Id
     */
    private $someId = '0';
}
