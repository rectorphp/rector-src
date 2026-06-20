<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

use Doctrine\ORM\Mapping\ClassMetadata;

final class SkipStaticFunctionMapping
{
    private $name;

    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $metadata->mapField([
            'fieldName' => 'name',
            'type' => 'string',
        ]);
    }
}
