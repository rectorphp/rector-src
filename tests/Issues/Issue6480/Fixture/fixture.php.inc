<?php

namespace Rector\Tests\Issues\Issue6480\Fixture;

use Rector\Tests\Issues\Issue6480\Source\ExistingClass;

class Fixture
{
    function test(ExistingClass $foo, mixed $bar): void {
        if ($foo instanceof ExistingClass) {
            var_dump(!empty($bar) ? $bar : null);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\Issue6480\Fixture;

use Rector\Tests\Issues\Issue6480\Source\ExistingClass;

class Fixture
{
    function test(ExistingClass $foo, mixed $bar): void {
        var_dump(empty($bar) ? null : $bar);
    }
}

?>
