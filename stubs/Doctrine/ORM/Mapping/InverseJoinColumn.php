<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\InverseJoinColumn')) {
    return;
}

final class InverseJoinColumn
{
}
