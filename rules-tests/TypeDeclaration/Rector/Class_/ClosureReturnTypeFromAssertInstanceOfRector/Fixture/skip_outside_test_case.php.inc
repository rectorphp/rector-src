<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ClosureReturnTypeFromAssertInstanceOfRector\Fixture;

final class SkipOutsideTestCase
{
    public function run()
    {
        $callback = function (object $event) {
            return $event;
        };
    }
}
