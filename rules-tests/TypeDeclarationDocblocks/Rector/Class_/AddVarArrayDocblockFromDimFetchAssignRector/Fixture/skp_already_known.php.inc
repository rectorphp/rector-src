<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddVarArrayDocblockFromDimFetchAssignRector\Fixture;

final class SkipAlreadyKnown
{
    /**
     * @var list<string>
     */
    private array $items = [];

    public function go()
    {
        $this->items[] = 'item1';
    }
}
