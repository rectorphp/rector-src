<?php

namespace Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Fixture;

interface FooInterface {
}

interface BarInterface extends FooInterface {
}

trait FooTrait {
}

class Bar implements BarInterface {
    use FooTrait;
}
?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Fixture;

interface FooInterface {
}

interface BarInterface extends FooInterface {
}

trait FooTrait {
}

class Bar implements BarInterface {
    use FooTrait;
}
?>
