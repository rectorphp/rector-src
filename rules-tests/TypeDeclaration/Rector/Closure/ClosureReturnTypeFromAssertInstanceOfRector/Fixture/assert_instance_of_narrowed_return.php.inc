<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeFromAssertInstanceOfRector\Fixture;

use PHPUnit\Framework\TestCase;

final class AssertInstanceOfNarrowedReturn extends TestCase
{
    public function test()
    {
        $callback = function (object $object) {
            $this->assertInstanceOf(\DateTime::class, $object);

            return $object;
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeFromAssertInstanceOfRector\Fixture;

use PHPUnit\Framework\TestCase;

final class AssertInstanceOfNarrowedReturn extends TestCase
{
    public function test()
    {
        $callback = function (object $object): \DateTime {
            $this->assertInstanceOf(\DateTime::class, $object);

            return $object;
        };
    }
}

?>
