<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\ManyToOne')) {
    return;
}

/**
 * @see https://github.com/doctrine/orm/blob/2.12.x/lib/Doctrine/ORM/Mapping/ManyToOne.php
 */
final class ManyToOne
{
    public function __construct(
        ?string $targetEntity = null,
        ?array $cascade = null,
        string $fetch = 'LAZY',
        ?string $inversedBy = null
    ) {
    }
}
