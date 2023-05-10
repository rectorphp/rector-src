<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

final class SkipArgUnpack
{
    /**
     * @param list<int> $list
     */
    public function __construct(array $list)
    {
        $this->process(...$list);
    }

    private function process(int ...$variadic): void
    {
    }
}
