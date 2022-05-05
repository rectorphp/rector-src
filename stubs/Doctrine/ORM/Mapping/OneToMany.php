<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\OneToMany')) {
    return;
}

/**
 * @see https://github.com/doctrine/orm/blob/2.12.x/lib/Doctrine/ORM/Mapping/OneToMany.php
 */
final class OneToMany
{
    public function __construct(
        ?string $mappedBy = null,
        ?string $targetEntity = null,
        ?array $cascade = null,
        string $fetch = 'LAZY',
        bool $orphanRemoval = false,
        ?string $indexBy = null
    ) {
    }
}
