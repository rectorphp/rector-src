<?php

namespace Rector\Tests\Unambiguous\Rector\Class_\RemoveReturnThisFromSetterClassMethodRector\Fixture;

final class SomeClass
{
    private string $nameValue;

    public function setName(string $name): self
    {
        $this->nameValue = $name;

        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\Unambiguous\Rector\Class_\RemoveReturnThisFromSetterClassMethodRector\Fixture;

final class SomeClass
{
    private string $nameValue;

    public function setName(string $name): void
    {
        $this->nameValue = $name;
    }
}

?>
