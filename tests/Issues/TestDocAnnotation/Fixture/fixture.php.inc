<?php

namespace Rector\Tests\Issues\TestDocAnnotation\Fixture;

use PHPUnit\Framework\TestCase;

final class Fixture extends TestCase
{
    /**
     * @testdox ::property is true
     */
    public function myAwesomeTest(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\TestDocAnnotation\Fixture;

use PHPUnit\Framework\TestCase;

final class Fixture extends TestCase
{
    #[\PHPUnit\Framework\Attributes\TestDox('property is true')]
    public function myAwesomeTest(): void
    {
    }
}

?>
