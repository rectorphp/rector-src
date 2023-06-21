<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SkipOnceTypeAndNativeCallSecond
{
    public function getData()
    {
        if (false) {
            return $this->getString();
        }

        return array_map(function ($i) {
            return $i;
        }, []);
    }

    private function getString()
    {
        return '...';
    }
}
