<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

class SkipAssignedInNamedConstructor
{
    private \DateTimeInterface $date;

    public function __construct() {
        $this->date = new \DateTime();
    }

    public static function create(\DateTimeInterface $date): self
    {
        $ack = new self();
        $ack->date = $date;

        return $ack;
    }
}
