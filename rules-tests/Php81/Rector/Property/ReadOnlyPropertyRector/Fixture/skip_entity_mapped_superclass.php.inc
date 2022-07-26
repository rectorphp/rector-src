<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class Oi
{
    #[ORM\Column(type: 'string')]
    #[ORM\Id]
    private string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }
}
