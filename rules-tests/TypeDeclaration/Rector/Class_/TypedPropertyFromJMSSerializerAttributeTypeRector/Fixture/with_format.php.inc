<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class WithFormat
{
    #[\JMS\Serializer\Annotation\Type("DateTime<'Y-m-d'>")]
    private $name;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class WithFormat
{
    #[\JMS\Serializer\Annotation\Type("DateTime<'Y-m-d'>")]
    private ?\DateTime $name;
}

?>
