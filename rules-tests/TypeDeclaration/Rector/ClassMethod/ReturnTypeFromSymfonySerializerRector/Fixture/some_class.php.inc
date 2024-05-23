<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromSymfonySerializerRector\Fixture;

final class SomeClass
{
    private \Symfony\Component\Serializer\Serializer $serializer;

    public function resolveEntity($data)
    {
        return $this->serializer->deserialize($data, \stdClass::class, 'json');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromSymfonySerializerRector\Fixture;

final class SomeClass
{
    private \Symfony\Component\Serializer\Serializer $serializer;

    public function resolveEntity($data): \stdClass
    {
        return $this->serializer->deserialize($data, \stdClass::class, 'json');
    }
}

?>
