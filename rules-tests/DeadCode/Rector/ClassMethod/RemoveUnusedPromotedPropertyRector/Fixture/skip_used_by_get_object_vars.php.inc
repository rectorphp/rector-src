<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

readonly class SkipUsedByGetObjectVars
{
  public function __construct(private string $foo)
  {
  }

  public function toArray(): array
  {
    return get_object_vars($this);
  }
}
