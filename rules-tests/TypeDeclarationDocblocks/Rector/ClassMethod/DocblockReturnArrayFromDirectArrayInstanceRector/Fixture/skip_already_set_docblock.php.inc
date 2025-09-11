<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class SkipAlreadySetDocblock
{
    /**
     * @return array<string, int|string>
     */
    public function getNames(): array
    {
        return [
            'key' => 'value',
        ];
    }
}
