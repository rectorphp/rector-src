<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\IssueEarlyReturnAndInsideCase\Fixture;

class ComplexIfCondAnd
{
    public function run($a, $b, $c, $d, $e, $f, $g)
    {
        if ($b && $c) {
            return true;
        } elseif ($d && $e) {
            return false;
        } elseif ($f && $g) {
            return 1;
        }

        return 0;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\IssueEarlyReturnAndInsideCase\Fixture;

class ComplexIfCondAnd
{
    public function run($a, $b, $c, $d, $e, $f, $g)
    {
        if ($b && $c) {
            return true;
        }
        if ($d && $e) {
            return false;
        }
        if (!$f) {
            return 0;
        }
        if (!$g) {
            return 0;
        }
        return 1;
    }
}

?>
