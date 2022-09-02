<?php

interface FooInterface {
}

interface BarInterface extends FooInterface {
}

trait FooTrait {
}

namespace Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Fixture;

class Bar implements BarInterface {
    use FooTrait;
}
?>
-----
<?php

interface FooInterface {
}

interface BarInterface extends FooInterface {
}

trait FooTrait {
}

namespace Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Fixture;

class Bar implements BarInterface {
    use FooTrait;
}
?>
