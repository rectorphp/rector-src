<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\AddTestsVoidReturnTypeWhereNoReturnRector\Fixture;

use PHPUnit\Framework\TestCase;

class SkipNoPublic extends TestCase
{
    protected function testSomething()
    {
    }
}
