<?php

declare(strict_types=1);

namespace Doctrine\Common\Collections;

use IteratorAggregate;

if (interface_exists('Doctrine\Common\Collections\Collection')) {
    return;
}

interface Collection extends IteratorAggregate
{

}
