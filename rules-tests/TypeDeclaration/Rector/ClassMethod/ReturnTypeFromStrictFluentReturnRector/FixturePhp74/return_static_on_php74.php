<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector\FixturePhp74;

class ReturnStatic
{
    private $foo = 'bar';

    public function test()
    {
        $obj = new static();
        $obj->foo = 'foo';

        return $obj;
    }
}
