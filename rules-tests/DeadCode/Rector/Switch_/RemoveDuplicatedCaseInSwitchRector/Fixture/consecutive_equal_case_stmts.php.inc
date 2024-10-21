<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class ConsecutiveEqualCaseStmts
{
    public function run()
    {
      $item = 'a';
      switch ($item) {
        case 'a':
        case 'b':
          return 'val1';
        case 'c':
          return 'val2';
        case 'd':
        case 'e':
          return 'val2';
        case 'f':
          return 'val2';
        case 'g':
          return 'val3';
      }
      return $item;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class ConsecutiveEqualCaseStmts
{
    public function run()
    {
      $item = 'a';
      switch ($item) {
        case 'a':
        case 'b':
          return 'val1';
        case 'c':
        case 'd':
        case 'e':
        case 'f':
          return 'val2';
        case 'g':
          return 'val3';
      }
      return $item;
    }
}

?>
