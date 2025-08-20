<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Switch_\ColonAfterSwitchCaseRector\Fixture;

final class WithoutStmt
{
    public function run()
    {
        switch ($value) {
            case 'baz';
        }
    }

    public function indirectStmt()
    {
        switch ($value) {
            case 'baz';
            case 'bar';
                echo 'test';
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Switch_\ColonAfterSwitchCaseRector\Fixture;

final class WithoutStmt
{
    public function run()
    {
        switch ($value) {
            case 'baz':
        }
    }

    public function indirectStmt()
    {
        switch ($value) {
            case 'baz':
            case 'bar':
                echo 'test';
        }
    }
}

?>
