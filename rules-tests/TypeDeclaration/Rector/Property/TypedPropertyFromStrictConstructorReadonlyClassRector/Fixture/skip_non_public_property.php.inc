<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorReadonlyClassRector\Fixture;

/**
 * @immutable
 */
class SkipNonPublicProperty
{
    protected $config; // non-public properties are handled by other rules

    public function __construct(string $s)
    {
        $this->config = $s;
    }
}
