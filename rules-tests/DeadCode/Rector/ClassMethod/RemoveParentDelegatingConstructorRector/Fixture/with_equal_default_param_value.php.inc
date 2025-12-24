<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

class WithEqualDefaultParamValue extends \DateTime
{
    public function __construct(string $datetime = "now", ?\DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

class WithEqualDefaultParamValue extends \DateTime
{
}

?>