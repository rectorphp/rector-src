<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class OnAssignOp
{
    private string|null $foo;

    public function __construct(string $string)
    {
        $this->foo = $string;
    }

    public function do(): void
    {
        $bar = '';

        if ($this->foo !== null) {
            $bar .= $this->foo;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class OnAssignOp
{
    private readonly string|null $foo;

    public function __construct(string $string)
    {
        $this->foo = $string;
    }

    public function do(): void
    {
        $bar = '';

        if ($this->foo !== null) {
            $bar .= $this->foo;
        }
    }
}

?>
