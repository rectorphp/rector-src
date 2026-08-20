<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Source;

interface SomeSearchInterface
{
    /**
     * @param array{alias?: string, id?: string} $criteria
     */
    public function findOneMatching(array $criteria): ?object;
}
