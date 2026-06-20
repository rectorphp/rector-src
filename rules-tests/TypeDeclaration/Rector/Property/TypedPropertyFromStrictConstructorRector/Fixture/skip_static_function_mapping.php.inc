<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

use Doctrine\ORM\Mapping\ClassMetadata;

final class SkipStaticFunctionMapping
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $metadata->mapField([
            'fieldName' => 'name',
            'type' => 'string',
        ]);
    }
}
