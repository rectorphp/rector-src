<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ScalarTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class ExactTypedAssigned
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private $name;

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ScalarTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class ExactTypedAssigned
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private ?string $name = null;

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}

?>
