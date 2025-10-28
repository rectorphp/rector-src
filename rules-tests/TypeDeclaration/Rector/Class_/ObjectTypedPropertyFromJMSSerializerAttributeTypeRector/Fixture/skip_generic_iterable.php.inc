<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class SkipGenericIterable
{
    #[\JMS\Serializer\Annotation\Type('iterable<SomeType>')]
    private $name;
}
