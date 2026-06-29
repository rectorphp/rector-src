<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeFromAssertInstanceOfRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipNoAssertInstanceOf extends TestCase
{
    public function test()
    {
        $callback = function (object $event) {
            return $event;
        };
    }
}
