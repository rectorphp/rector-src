<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class SkipDirectCallableWithRange2ndArg
{
    public function getRandomData(): array
    {
        return array_map(fn (): int => random_int(1, 100), range(0, 23));
    }
}
