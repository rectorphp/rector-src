<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class SomeClass
{
    private $name = 'John';

    public function setName(string $name): void
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

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class SomeClass
{
    private string $name = 'John';

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

?>
