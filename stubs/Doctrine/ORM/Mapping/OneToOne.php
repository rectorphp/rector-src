<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\OneToOne')) {
    return;
}

/**
 * @see https://github.com/doctrine/orm/blob/2.12.x/lib/Doctrine/ORM/Mapping/OneToOne.php
 */
final class OneToOne
{
    public function __construct(
        ?string $mappedBy = null,
        ?string $inversedBy = null,
        ?string $targetEntity = null,
        ?array $cascade = null,
        string $fetch = 'LAZY',
        bool $orphanRemoval = false
    ) {
    }
}
