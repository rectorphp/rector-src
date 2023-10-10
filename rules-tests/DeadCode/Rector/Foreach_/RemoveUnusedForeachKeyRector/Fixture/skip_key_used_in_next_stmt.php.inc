<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

final class SkipKeyUsedInNexStmt
{
    function lastKey ($container, $defKey = null) {
        $result = $defKey;

        foreach ($container as $result => $value) {
          // next
        }

        return $result;
      }
}
