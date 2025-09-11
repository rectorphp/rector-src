<?php
declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Class_\WakeupToUnserializeRector\Fixture;

class User {
    private $id;
    private $name;

    public function __wakeup(){
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

    public function __unserialize(array $data): void{
        foreach ($data as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}
?>
