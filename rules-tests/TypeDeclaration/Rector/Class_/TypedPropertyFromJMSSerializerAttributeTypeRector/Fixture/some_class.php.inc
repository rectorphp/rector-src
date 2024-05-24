<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class SomeClass
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private $name;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class SomeClass
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private ?string $name = null;
}

?>
