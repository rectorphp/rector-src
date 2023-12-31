<?php

namespace Rector\Tests\Issues\IssueAndIfCompleteDeMorgan\Fixture;

use Rector\Tests\Issues\IssueAndIfCompleteDeMorgan\Source\ParentClassWithLetter;

final class Fixture extends ParentClassWithLetter
{
    public function run($a, $b, $c)
    {
        if (($a === 'a' || $b === 'b') && $c) {
            $this->d = 'd';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\IssueAndIfCompleteDeMorgan\Fixture;

use Rector\Tests\Issues\IssueAndIfCompleteDeMorgan\Source\ParentClassWithLetter;

final class Fixture extends ParentClassWithLetter
{
    public function run($a, $b, $c)
    {
        if ($a !== 'a' && $b !== 'b') {
            return;
        }
        if (!$c) {
            return;
        }
        $this->d = 'd';
    }
}

?>
