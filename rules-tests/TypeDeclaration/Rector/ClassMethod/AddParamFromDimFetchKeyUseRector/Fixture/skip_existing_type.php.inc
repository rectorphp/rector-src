<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Fixture;

final class SkipExistingType
{
    public function process(int $key): void
    {
        $items = [
            'first' => 'Firstitem',
            'second' => 'Seconditem',
        ];

        return $items[$key];
    }
}
