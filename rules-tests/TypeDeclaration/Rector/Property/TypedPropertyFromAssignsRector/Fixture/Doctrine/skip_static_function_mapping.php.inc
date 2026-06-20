<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;

final class SkipStaticFunctionMapping
{
    private $name;

    public function run()
    {
        $this->name = 'string';
    }

    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $metadata->mapField([
            'fieldName' => 'name',
            'type' => 'string',
        ]);
    }
}
