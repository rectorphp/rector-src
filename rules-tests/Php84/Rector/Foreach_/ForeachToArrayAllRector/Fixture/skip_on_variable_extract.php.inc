<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class SkipOnVariableExtract
{
  private function allDependencyHasType(array $dependencies, string $type): bool {
    foreach ($dependencies as ['type' => $currentType]) {
      if ($currentType !== $type) {
        return false;
      }
    }

    return true;
  }
}
