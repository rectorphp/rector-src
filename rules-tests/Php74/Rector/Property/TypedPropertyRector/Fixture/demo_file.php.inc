<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

use PHPUnit\Framework\MockObject\MockObject;

class DemoFile
{
  /** @var Foo|mixed|MockObject */
  private $x;
}
?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

use PHPUnit\Framework\MockObject\MockObject;

class DemoFile
{
  /** @var Foo|mixed|MockObject */
  private $x;
}
?>
