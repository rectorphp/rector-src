<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

class SkipUsedByArrayCallable
{
  private static function bar(string $a, string $b): int
  {
    return $a <=> $b;
  }

  public static function foo(): void
  {
    $array = [];
    usort($array, [self::class, 'bar']);
  }
}
