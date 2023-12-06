<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

final class AssignThisBecomeSelf
{
    private static $instance = null;

    private function __construct()
    {
        static::$instance = $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

final class AssignThisBecomeSelf
{
    private static ?self $instance = null;

    private function __construct()
    {
        static::$instance = $this;
    }
}

?>
