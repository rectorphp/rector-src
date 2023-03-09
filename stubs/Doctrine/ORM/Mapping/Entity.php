<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\Entity')) {
    return;
}

final class Entity
{
    public function __construct(?string $repositoryClass = null, bool $readOnly = false)
    {
    }
}
