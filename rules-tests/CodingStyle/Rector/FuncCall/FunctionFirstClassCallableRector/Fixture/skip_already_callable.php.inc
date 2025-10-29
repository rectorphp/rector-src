<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class SkipAlreadyCallable
{
    public function skipFilter(array $data)
    {
        return array_filter($data, boolval(...));
    }
}
