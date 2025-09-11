<?php
declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Class_\NullDebugInfoReturnRector\Fixture;

class SkipSleepNull {
    private $id;
    private $name;

    public function __sleep() {
        return null;
    }
}
?>
