<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeTestCaseClassRector\Fixture;

use PHPUnit\Framework\TestCase;

class SomeClass extends TestCase
{
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Class_\FinalizeTestCaseClassRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SomeClass extends TestCase
{
}

?>
