<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SkipInConditional
{
    private array $array = [];

    /** @param array|null $arrayOrNull */
    public function __construct($arrayOrNull) {
        if ($arrayOrNull !== null) {
            $this->array = $arrayOrNull;
        }
    }
}
