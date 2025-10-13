<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\Fixture;

final class SkipMixClearAndUnClearType
{
    public function process($key, $unclearType)
    {
        if (rand(0, 1)) {
            $items = [
                'first' => 'Firstitem',
                'second' => 'Seconditem',
            ];

            return $items[$key];
        }

        return $unclearType[$key];
    }
}
