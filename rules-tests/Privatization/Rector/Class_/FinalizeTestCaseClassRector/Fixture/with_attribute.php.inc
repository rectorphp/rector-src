<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeTestCaseClassRector\Fixture;

use PHPUnit\Framework\TestCase;

#[CoversClass(Advisor::class)]
class WithAttribute extends TestCase
{
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeTestCaseClassRector\Fixture;

use PHPUnit\Framework\TestCase;

#[CoversClass(Advisor::class)]
final class WithAttribute extends TestCase
{
}

?>
