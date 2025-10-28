<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use JMS\Serializer\Annotation\Type;
use Rector\Tests\TypeDeclaration\Rector\Class_\ObjectTypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

final class SkipToManyAndToOne
{
    #[Type(SomeClassInSerializer::class)]
    #[MongoDB\EmbedMany(targetDocument: SomeClassInSerializer::class)]
    private $someClasses;

    #[Type('string')]
    #[MongoDB\ReferenceOne(targetDocument: SomeClassInSerializer::class)]
    private $someObject;
}
