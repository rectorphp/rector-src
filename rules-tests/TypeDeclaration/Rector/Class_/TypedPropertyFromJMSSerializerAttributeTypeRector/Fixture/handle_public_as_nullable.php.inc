<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer;

final class HandlePublicAsNullable
{
    #[\JMS\Serializer\Annotation\Type(SomeClassInSerializer::class)]
    public $name;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer;

final class HandlePublicAsNullable
{
    #[\JMS\Serializer\Annotation\Type(SomeClassInSerializer::class)]
    public ?\Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer $name = null;
}

?>
