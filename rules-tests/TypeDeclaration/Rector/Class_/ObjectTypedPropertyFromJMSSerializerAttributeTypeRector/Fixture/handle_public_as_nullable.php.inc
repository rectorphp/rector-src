<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer;

final class HandlePublicAsNullable
{
    #[\JMS\Serializer\Annotation\Type(SomeClassInSerializer::class)]
    public $name;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer;

final class HandlePublicAsNullable
{
    #[\JMS\Serializer\Annotation\Type(SomeClassInSerializer::class)]
    public ?\Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer $name = null;
}

?>
