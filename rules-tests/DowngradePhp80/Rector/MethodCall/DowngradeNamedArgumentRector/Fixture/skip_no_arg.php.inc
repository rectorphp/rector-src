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

class SkipNoArg extends Foo
{
    public function __construct(string $name = null, array $attributes = [])
    {
        parent::__construct();
    }
}

?>
