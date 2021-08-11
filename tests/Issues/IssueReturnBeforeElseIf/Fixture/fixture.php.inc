<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueReturnBeforeElseIf\Fixture;

class ComplexAndOr
{
    public function run($a, $b, $c, $d)
    {
        if (($a && false === $b) || ! $c) {
            return '';
        } elseif ($d) {
            return '';
        }

        return $d;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IssueReturnBeforeElseIf\Fixture;

class ComplexAndOr
{
    public function run($a, $b, $c, $d)
    {
        if ($a && false === $b) {
            return '';
        }
        if (! $c) {
            return '';
        }
        return $d;
    }
}

?>
