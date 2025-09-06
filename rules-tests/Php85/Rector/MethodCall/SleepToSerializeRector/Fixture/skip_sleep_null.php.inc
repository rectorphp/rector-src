<?php
declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\MethodCall\NullDebugInfoReturnRector\Fixture;

class User {
    private $id;
    private $name;

    public function __sleep() {
        return null;
    }
}
?>
