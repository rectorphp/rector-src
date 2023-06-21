<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class SkipOnceTypeAndNativeCallFirst
{
    public function getData()
    {
        if (false) {
            return array_map(function ($i) {
                return $i;
            }, []);
        }

        return $this->getString();
    }

    private function getString()
    {
        return '...';
    }
}
