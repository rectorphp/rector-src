<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

final class SkipMultiTypesReturn
{
    public function getArray($action)
    {
        switch ($action) {
            case 'save':
                return 17;
            default:
                return null;
        }
        return [];
    }
}