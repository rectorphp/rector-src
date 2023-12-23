<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Source\ReturnSelfFromSource;

final class ReturnSelfDifferentNamespace
{
    public function run()
    {
        return
            array_map(function () {
                return ReturnSelfFromSource::fromEvent();
            }, ['event']);
    }
}

?>
