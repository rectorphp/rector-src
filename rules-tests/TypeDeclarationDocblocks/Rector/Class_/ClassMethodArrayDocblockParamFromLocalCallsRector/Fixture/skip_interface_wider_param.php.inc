<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source\SomeSearchInterface;

final class SkipInterfaceWiderParam implements SomeSearchInterface
{
    public function findOneMatching(array $criteria): ?object
    {
        return null;
    }

    public function go(string $id): ?object
    {
        return $this->findOneMatching(['id' => $id]);
    }
}
