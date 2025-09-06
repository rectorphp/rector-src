<?php
declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\MethodCall\NullDebugInfoReturnRector\Fixture;

class User {
    private $id;
    private $name;

    public function __sleep() {
        return ['id', 'name'];
    }
}
?>
-----
<?php
declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\MethodCall\NullDebugInfoReturnRector\Fixture;

class User {
    private $id;
    private $name;

    public function __serialize(): array {
        return ['id' => $this->id, 'name' => $this->name];
    }
}
?>
