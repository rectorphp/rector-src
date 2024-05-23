<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromSymfonySerializerRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromSymfonySerializerRector\Source\DifferentType;

final class SkipDifferentObject
{
    private DifferentType $serializer;

    public function resolveEntity($data)
    {
        return $this->serializer->deserialize($data, \stdClass::class, 'json');
    }
}