<?php

namespace Rector\Tests\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector\Fixture;

final class SkipSimpleReturn
{
    public function getEerrors($fileName)
    {
        if (is_string($fileName) && str_contains($fileName, 'vendor')) {
            return [];
        }

        return ['some error'];
    }
}
