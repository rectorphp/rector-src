<?php

namespace Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Fixture;

use PHPUnit\Framework\TestCase;

class AssertEqualsUseSelf extends TestCase
{
    public function test()
    {
        $this->assertEquals(1, 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector\Fixture;

use PHPUnit\Framework\TestCase;

class AssertEqualsUseSelf extends TestCase
{
    public function test()
    {
        self::assertEquals(1, 1);
    }
}

?>
