<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\ManyToMany')) {
    return;
}

/**
 * @see https://github.com/doctrine/orm/blob/2.12.x/lib/Doctrine/ORM/Mapping/ManyToMany.php
 */
final class ManyToMany
{
    public function __construct(
        ?string $targetEntity = null,
        ?string $mappedBy = null,
        ?string $inversedBy = null,
        ?array $cascade = null,
        string $fetch = 'LAZY',
        bool $orphanRemoval = false,
        ?string $indexBy = null
    ) {
    }
}
