<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

final class SkipUndirectArrayReturn
{
    public function getArray(array $items)
    {
        return $items;
    }
}
