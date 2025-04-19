<?php

namespace Rector\Tests\Issues\TestDocAnnotation\Fixture;

use PHPUnit\Framework\TestCase;

final class MultiDoubleColon extends TestCase
{
    /**
     * @testdox ::property is true ::property2 is true as well
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

final class MultiDoubleColon extends TestCase
{
    #[\PHPUnit\Framework\Attributes\TestDox('::property is true ::property2 is true as well')]
    public function myAwesomeTest(): void
    {
    }
}

?>
