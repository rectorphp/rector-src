<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use JMS\Serializer\Annotation\Type;
use Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer;

final class ExactClass
{
    #[Type(SomeClassInSerializer::class)]
    private $name;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use JMS\Serializer\Annotation\Type;
use Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer;

final class ExactClass
{
    #[Type(SomeClassInSerializer::class)]
    private ?\Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer $name = null;
}

?>
