<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\ManyToMany')) {
    return;
}

#[\Attribute]
final class ManyToMany
{
    public function __construct($targetEntity)
    {
    }
}
