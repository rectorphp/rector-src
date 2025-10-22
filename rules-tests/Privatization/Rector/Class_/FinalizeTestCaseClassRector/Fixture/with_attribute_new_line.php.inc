<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeTestCaseClassRector\Fixture;

use PHPUnit\Framework\TestCase;

#[CoversClass(Advisor::class)]
class WithAttributeNewLine extends TestCase
{
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeTestCaseClassRector\Fixture;

use PHPUnit\Framework\TestCase;

#[CoversClass(Advisor::class)]
final class WithAttributeNewLine extends TestCase
{
}

?>
