<?php

namespace Rector\Tests\Php84\Rector\Class_\PropertyHookRector\Fixture;

final class AllowGetterAndReadonly
{
    private readonly string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Class_\PropertyHookRector\Fixture;

final class AllowGetterAndReadonly
{
    private readonly string $name {
        get => $this->name;
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

?>
