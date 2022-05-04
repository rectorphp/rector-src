<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\OneToMany')) {
    return;
}

final class OneToMany
{
    public function __construct($targetEntity)
    {
    }
}
