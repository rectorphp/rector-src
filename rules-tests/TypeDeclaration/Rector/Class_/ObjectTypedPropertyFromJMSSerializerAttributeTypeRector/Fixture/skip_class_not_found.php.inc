<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use JMS\Serializer\Annotation as JMS;

final class SkipClassNotFound
{
    #[JMS\Type('SomeNonExistentClass')]
    protected $dynamicRef;
}
