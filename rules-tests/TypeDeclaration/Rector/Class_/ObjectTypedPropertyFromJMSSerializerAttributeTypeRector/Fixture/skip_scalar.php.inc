<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use JMS\Serializer\Annotation\Type;

final class SkipScalar
{
    /**
     * @var string
     */
    #[Type('float')]
    private $price;
}
