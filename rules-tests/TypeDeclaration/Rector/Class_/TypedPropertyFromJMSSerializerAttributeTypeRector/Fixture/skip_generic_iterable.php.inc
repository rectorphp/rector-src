<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class SkipGenericIterable
{
    #[\JMS\Serializer\Annotation\Type('iterable<SomeType>')]
    private $name;
}
