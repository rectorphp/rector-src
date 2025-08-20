<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Switch_\ColonAfterSwitchCaseRector\Fixture;

final class Fixture
{
    public function run()
    {
        switch ($value) {
            case 'baz';
                echo 'baz';
        }
    }

    public function spaceBeforeSemiColon()
    {
        switch ($value) {
            case 'baz' ;
                echo 'baz';
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Switch_\ColonAfterSwitchCaseRector\Fixture;

final class Fixture
{
    public function run()
    {
        switch ($value) {
            case 'baz':
                echo 'baz';
        }
    }

    public function spaceBeforeSemiColon()
    {
        switch ($value) {
            case 'baz' :
                echo 'baz';
        }
    }
}

?>
