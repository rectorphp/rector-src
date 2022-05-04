<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\OneToOne')) {
    return;
}

final class OneToOne
{
    public function __construct($targetEntity)
    {
    }
}
