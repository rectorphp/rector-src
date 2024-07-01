<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class NonTypedAssigned
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private $name;

    public function setName($name): void
    {
        $this->name = $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class NonTypedAssigned
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private ?string $name = null;

    public function setName($name): void
    {
        $this->name = $name;
    }
}

?>
