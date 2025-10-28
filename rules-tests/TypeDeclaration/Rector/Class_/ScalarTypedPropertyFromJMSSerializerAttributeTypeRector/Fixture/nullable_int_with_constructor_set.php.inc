<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ScalarTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class NullableIntWithConstructor
{
    #[\JMS\Serializer\Annotation\Type('int')]
    protected $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ScalarTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class NullableIntWithConstructor
{
    #[\JMS\Serializer\Annotation\Type('int')]
    protected ?int $number = null;

    public function __construct(int $number)
    {
        $this->number = $number;
    }
}

?>
