<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\Embedded')) {
    return;
}

/**
 * @see https://github.com/doctrine/orm/blob/2.12.x/lib/Doctrine/ORM/Mapping/Embedded.php
 */
final class Embedded
{
    public function __construct(?string $class = null, $columnPrefix = null)
    {
    }
}
