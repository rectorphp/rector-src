<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class WithPropertyHook
{
    private string $foo {
        get => strtoupper($this->foo);
        set {
            $this->foo = $value;
        }
    }

    public function __construct(
        string $foo,
    ) {
        $this->foo = $foo;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class WithPropertyHook
{
    public function __construct(private string $foo {
        get => strtoupper($this->foo);
        set {
            $this->foo = $value;
        }
    })
    {
    }
}

?>
