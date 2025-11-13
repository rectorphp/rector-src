<?php

namespace Rector\Tests\Unambiguous\Rector\Class_\RemoveReturnThisFromSetterClassMethodRector\Fixture;

final class DifferentSet
{
    private string $name;

    public function setName(string $name): self
    {
        $this->name = 100;

        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\Unambiguous\Rector\Class_\RemoveReturnThisFromSetterClassMethodRector\Fixture;

final class DifferentSet
{
    private string $name;

    public function setName(string $name): void
    {
        $this->name = 100;
    }
}

?>
