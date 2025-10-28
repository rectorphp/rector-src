<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use JMS\Serializer\Annotation\Type;

final class FloatVarString
{
    /**
     * @var string
     */
    #[Type('float')]
    private $price;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use JMS\Serializer\Annotation\Type;

final class ExactClass
{
    #[Type('float')]
    private ?string $price = null;
}

?>
