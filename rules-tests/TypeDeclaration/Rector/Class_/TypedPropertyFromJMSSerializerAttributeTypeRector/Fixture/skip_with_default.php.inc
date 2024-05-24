<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

final class SkipWithDefault
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private $name = 'foo';
}
