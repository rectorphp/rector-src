<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Fixture;

use JMS\Serializer\Annotation\Type;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\Source\SomeClassInSerializer;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

final class SkipToMany
{
    #[Type(SomeClassInSerializer::class)]
    #[MongoDB\EmbedMany(targetDocument: SomeClassInSerializer::class)]
    private $someClasses;
}
