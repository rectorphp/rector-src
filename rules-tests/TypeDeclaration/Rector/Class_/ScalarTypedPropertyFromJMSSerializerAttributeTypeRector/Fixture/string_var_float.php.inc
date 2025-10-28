<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ScalarTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use JMS\Serializer\Annotation\Type;

final class StringVarFloat
{
    /**
     * @var float
     */
    #[Type('string')]
    private $price;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ScalarTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use JMS\Serializer\Annotation\Type;

final class StringVarFloat
{
    #[Type('string')]
    private ?float $price = null;
}

?>
