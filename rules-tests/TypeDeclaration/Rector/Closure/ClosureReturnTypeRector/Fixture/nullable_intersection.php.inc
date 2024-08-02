<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NullableIntersection extends TestCase
{
    private (\Exception&MockObject)|null $mock = null;

    protected function setUp(): void
    {
        $this->mock = $this->createMock(\Exception::class);
    }

    public function testConfigureOptions(): void
    {
        $matcher = static::exactly(6);
        $this->mock
            ->expects($matcher)
            ->method('__toString')
            ->willReturnCallback(function (){
                return $this->mock;
            })
        ;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector\Fixture;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NullableIntersection extends TestCase
{
    private (\Exception&MockObject)|null $mock = null;

    protected function setUp(): void
    {
        $this->mock = $this->createMock(\Exception::class);
    }

    public function testConfigureOptions(): void
    {
        $matcher = static::exactly(6);
        $this->mock
            ->expects($matcher)
            ->method('__toString')
            ->willReturnCallback(function (){
                return $this->mock;
            })
        ;
    }
}

?>
