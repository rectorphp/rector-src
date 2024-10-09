<?php

namespace Rector\Tests\DeadCode\Rector\ClassLike\RemoveMockObjectPropertyAnnotationRector\Fixture;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class SomeTest extends TestCase
{
    /**
     * @var SomeClass|MockObject
     */
    private MockObject $someProperty;
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassLike\RemoveMockObjectPropertyAnnotationRector\Fixture;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class SomeTest extends TestCase
{
    private MockObject $someProperty;
}

?>
