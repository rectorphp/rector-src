<?php

declare(strict_types=1);

namespace Doctrine\ORM\Mapping;

if (class_exists('Doctrine\ORM\Mapping\Column')) {
    return;
}

#[\Attribute]
final class Column
{

}
