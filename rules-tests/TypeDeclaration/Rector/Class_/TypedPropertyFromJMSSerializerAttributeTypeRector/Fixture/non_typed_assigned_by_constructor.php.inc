<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class NonTypedAssignedByConstructor
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class NonTypedAssignedByConstructor
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private string $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}

?>
