<?php

namespace Rector\Tests\DowngradePhp80\Rector\MethodCall\DowngradeNamedArgumentRector\Fixture;

class Foo
{
    public function __construct(
        public ?array $a = null,
        public ?array $b = null
    ) {
    }
}

class Fixture extends Foo
{
    public function __construct(string $name = null, array $attributes = [])
    {
        parent::__construct(b: [[$name ?? 0 => $attributes]]);
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\MethodCall\DowngradeNamedArgumentRector\Fixture;

class Foo
{
    public function __construct(
        public ?array $a = null,
        public ?array $b = null
    ) {
    }
}

class Fixture extends Foo
{
    public function __construct(string $name = null, array $attributes = [])
    {
        parent::__construct(null, [[$name ?? 0 => $attributes]]);
    }
}

?>