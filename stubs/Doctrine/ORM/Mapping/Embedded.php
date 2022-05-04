<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\Embedded')) {
    return;
}

final class Embedded
{
    public function __construct($targetEntity)
    {
    }
}
