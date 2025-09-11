<?php
declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Class_\WakeupToUnserializeRector\Fixture;

class User {
    private $id;
    private $name;

    public function __wakeup(): void{
        $this->id = 1;
    }
}
?>
-----
<?php
declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Class_\WakeupToUnserializeRector\Fixture;

class User {
    private $id;
    private $name;

    public function __unserialize(): void{
        $this->id = 1;
    }
}
?>
