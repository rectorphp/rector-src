<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Source\ParentWithoutConstructor;

function createStub()
{
    return new class extends ParentWithoutConstructor {
        public function __construct()
        {
        }
    };
}

?>
