<?php

namespace Rector\Tests\Strict\Rector\ClassMethod\AddConstructorParentCallRector\Fixture;

use Rector\Tests\Strict\Rector\ClassMethod\AddConstructorParentCallRector\Source\ParentClassWithConstructor;

class SunshineCommand extends ParentClassWithConstructor
{
    public function __construct(private \stdClass $stdClass)
    {
        $value = 5;
    }
}

?>
-----
<?php

namespace Rector\Tests\Strict\Rector\ClassMethod\AddConstructorParentCallRector\Fixture;

use Rector\Tests\Strict\Rector\ClassMethod\AddConstructorParentCallRector\Source\ParentClassWithConstructor;

class SunshineCommand extends ParentClassWithConstructor
{
    public function __construct(private \stdClass $stdClass)
    {
        $value = 5;
        parent::__construct($stdClass);
    }
}

?>
