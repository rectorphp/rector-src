<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class DateTimeWithFormat
{
    #[\JMS\Serializer\Annotation\Type("DateTime<'Y-m-d'>")]
    private $name;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class DateTimeWithFormat
{
    #[\JMS\Serializer\Annotation\Type("DateTime<'Y-m-d'>")]
    private ?\DateTime $name = null;
}

?>
