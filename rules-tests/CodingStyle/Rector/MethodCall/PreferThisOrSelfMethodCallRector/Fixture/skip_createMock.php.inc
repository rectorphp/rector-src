<?php

namespace Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Fixture;

use Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Source\AbstractTestCase;

class ToSelf extends AbstractTestCase
{
    public function run()
    {
        $this->createMock(\Foo::class);
    }
}

?>
