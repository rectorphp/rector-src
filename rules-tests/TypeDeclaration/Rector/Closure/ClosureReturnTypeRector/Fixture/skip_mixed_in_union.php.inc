<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

final class SkipMixedInUnion
{
    public function run(bool $param): void
    {
        array_reduce($data, function (mixed $x) {
          	if (rand(0, 1)) {
             	return 'a';
            }

            return $x;
        });
    }
}
