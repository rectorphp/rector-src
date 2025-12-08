<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Source\SomeCacheInterface;

class SkipCallableParamUnion {
  public function __construct(
    private readonly SomeCacheInterface $cache,
  ) {
  }

  public function get(): int {
    return $this->cache->get('bar', fn() => time());
  }
}
