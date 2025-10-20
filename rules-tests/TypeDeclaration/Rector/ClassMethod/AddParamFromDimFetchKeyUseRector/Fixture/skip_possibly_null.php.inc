<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Fixture;

final class SkipPossiblyNull
{
    public function process($key)
    {
        if ($key === null) {
            return;
        }

        $items = [
            'first' => 'Firstitem',
            111 => 'Seconditem',
            'second' => 'Seconditem',
        ];

        return $items[$key];
    }
}
