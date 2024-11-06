<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class SkipDoctrineEmbeddable
{
    #[ORM\Column]
    private int $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }
}
