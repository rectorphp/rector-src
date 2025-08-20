<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Switch_\ColonAfterSwitchCaseRector\Fixture;

final class SkipWithColon
{
    public function run()
    {
        switch ($value) {
            case 'baz':
                echo 'baz';
        }
    }
}
