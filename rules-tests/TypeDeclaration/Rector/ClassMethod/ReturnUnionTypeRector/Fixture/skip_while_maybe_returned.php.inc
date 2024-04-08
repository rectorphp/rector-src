<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector\Fixture;

final class SkipWhileMaybeNeverReturned
{
    public function run(int $i)
    {
        while (++$i < 1) {
            if (rand(0,1)) {
                return 1;
            }

            return '1';
        }
    }
}
