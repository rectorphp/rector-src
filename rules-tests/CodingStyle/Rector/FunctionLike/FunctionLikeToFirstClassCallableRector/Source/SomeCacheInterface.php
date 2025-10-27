<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Source;

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
