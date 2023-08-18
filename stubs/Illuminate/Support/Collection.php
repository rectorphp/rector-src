<?php

namespace Illuminate\Support;

use Traversable;

if (class_exists('Illuminate\Support\Collection')) {
    return;
}

class Collection implements \ArrayAccess, \IteratorAggregate
{
    public function offsetGet(mixed $offset): mixed
    {
        // TODO: Implement offsetGet() method.
    }

    public function getIterator(): Traversable
    {
        // TODO: Implement getIterator() method.
    }

    public function offsetExists(mixed $offset): bool
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset(mixed $offset): void
    {
        // TODO: Implement offsetUnset() method.
    }
}
