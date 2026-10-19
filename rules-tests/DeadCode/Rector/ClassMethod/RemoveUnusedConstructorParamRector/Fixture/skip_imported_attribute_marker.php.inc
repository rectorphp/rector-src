<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

use Attribute;
use Rector\Contract\Rector\RectorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final class SkipImportedAttributeMarker
{
    /**
     * @param RectorInterface $someClass
     */
    public function __construct(string $someClass)
    {
    }
}
