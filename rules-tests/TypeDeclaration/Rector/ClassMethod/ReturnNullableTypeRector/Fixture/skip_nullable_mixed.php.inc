<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNullableTypeRector\Fixture;

final class SkipNullableMixed
{
    private $someMixed;

    public function run($value)
    {
        if ($value) {
            return $this->someMixed;
        }

        return null;
    }
}
