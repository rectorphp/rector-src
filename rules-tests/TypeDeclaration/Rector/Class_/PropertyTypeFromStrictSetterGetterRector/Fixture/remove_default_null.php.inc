<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class RemoveDefaultNull
{
    private $name = null;

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

final class RemoveDefaultNull
{
    private string $name;

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
