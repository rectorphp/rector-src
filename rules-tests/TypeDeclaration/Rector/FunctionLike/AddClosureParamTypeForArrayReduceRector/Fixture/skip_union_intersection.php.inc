<?php

// no namespace on purpose to produce error, see https://getrector.com/demo/04651a65-55f1-4a8e-9b1c-f4244b8334d1

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

$items = ['a', 'b', 'b', 'c'];

$reduces = \array_reduce($items, static function (Collection $carry, string $item) {
    if (!$carry->contains($item)) {
        $carry->add($item);
    }

    return $carry;
}, new ArrayCollection());