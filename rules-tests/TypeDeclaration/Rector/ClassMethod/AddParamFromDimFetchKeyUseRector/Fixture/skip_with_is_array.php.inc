<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Source\SomeObjectValidation;

class SkipWithIsArray
{
    /**
     * @param SomeObjectValidation $object
     * @param array<int, int|string>|string $key
     */
    public function __construct($object, $key)
    {
        if (\is_array($key) && (string) $object->{$key[1]} !== '') {
        }

        if (isset($object->validation_errors[$key])) {
        }
    }

}
