<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Source;

class Repository
{
    /**
     * @template TCacheValue
     * @return (TCacheValue is null ? mixed : TCacheValue)
     */
    public function get($key, $default = null): mixed
    {
    }
}