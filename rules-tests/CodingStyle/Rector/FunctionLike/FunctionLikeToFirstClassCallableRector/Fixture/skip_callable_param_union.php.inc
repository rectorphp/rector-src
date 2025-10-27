<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Source\SomeCacheInterface;

class SkipCallableParamUnion {
  public function __construct(
    private readonly SomeCacheInterface $cache,
  ) {
  }

  public function get(): int {
    return $this->cache->get('bar', fn() => time());
  }
}
