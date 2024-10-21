<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class ConsecutiveEqualCaseStmts2
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
        case 'g':
          return 'val2';
        case 'h':
          return 'val2';
        case 'i':
          return 'val3';
      }
      return $item;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class ConsecutiveEqualCaseStmts2
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
        case 'g':
        case 'h':
          return 'val2';
        case 'i':
          return 'val3';
      }
      return $item;
    }
}

?>
