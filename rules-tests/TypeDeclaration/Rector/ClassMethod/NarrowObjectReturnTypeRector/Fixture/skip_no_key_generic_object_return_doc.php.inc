<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

final class SkipNoKeyGenericObjectReturnDoc
{
    /**
     * @return \Iterator<\stdClass>
     */
    public function run(): \Iterator
    {
        $arr = [new \stdClass];
        return new \ArrayIterator($arr);
    }
}
