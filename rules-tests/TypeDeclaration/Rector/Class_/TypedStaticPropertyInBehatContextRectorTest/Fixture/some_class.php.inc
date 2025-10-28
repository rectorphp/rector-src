<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedStaticPropertyInBehatContextRectorTest\Fixture;

use Behat\Behat\Context\Context;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedStaticPropertyInBehatContextRectorTest\Source\OptionalBehatProperty;

final class SomeClass implements Context
{
    /**
     * @var OptionalBehatProperty
     */
    public static $type;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedStaticPropertyInBehatContextRectorTest\Fixture;

use Behat\Behat\Context\Context;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedStaticPropertyInBehatContextRectorTest\Source\OptionalBehatProperty;

final class SomeClass implements Context
{
    public static ?\Rector\Tests\TypeDeclaration\Rector\Class_\TypedStaticPropertyInBehatContextRectorTest\Source\OptionalBehatProperty $type = null;
}

?>
