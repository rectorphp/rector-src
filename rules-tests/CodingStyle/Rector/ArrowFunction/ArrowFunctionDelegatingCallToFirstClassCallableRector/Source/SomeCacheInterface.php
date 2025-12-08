<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Source;

use Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Source\CallbackInterface;
use Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Source\InvalidArgumentException;

interface SomeCacheInterface {
    /**
     * @template T
     *
     * @param string $key
     * @param (callable(CacheItemInterface,bool):T)|(callable(ItemInterface,bool):T)|CallbackInterface<T> $callback
     * @param float|null $beta
     * @param array      &$metadata
     *
     * @return T
     *
     * @throws InvalidArgumentException When $key is not valid or when $beta is negative
     */
    public function get(string $key, callable $callback, ?float $beta = null, ?array &$metadata = null): mixed;
}
