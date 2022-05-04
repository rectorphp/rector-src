<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\ManyToOne')) {
    return;
}

final class ManyToOne
{
    public function __construct($targetEntity)
    {
    }
}
