<?php

namespace Rector\Tests\Unambiguous\Rector\Class_\RemoveReturnThisFromSetterClassMethodRector\Fixture;

final class SomeClass
{
    private string $name;

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\Unambiguous\Rector\Class_\RemoveReturnThisFromSetterClassMethodRector\Fixture;

final class SomeClass
{
    private string $name;

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}

?>
