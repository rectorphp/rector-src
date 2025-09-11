<?php
declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Class_\NullDebugInfoReturnRector\Fixture;

class SkipSleepEmpty {
    private $id;
    private $name;

    public function __sleep(): array {
        return [];
    }
}
?>
?>
