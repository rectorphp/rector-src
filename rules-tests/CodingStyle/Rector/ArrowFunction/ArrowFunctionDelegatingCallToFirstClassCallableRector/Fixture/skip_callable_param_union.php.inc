<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Source\SomeCacheInterface;

class SkipCallableParamUnion {
  public function __construct(
    private readonly SomeCacheInterface $cache,
  ) {
  }

  public function get(): int {
    return $this->cache->get('bar', fn() => time());
  }
}
