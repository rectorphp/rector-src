<?php

namespace Rector\Tests\Carbon\Rector\MethodCall\DateTimeMethodCallToCarbonRector\Fixture;

class SomeClass
{
    public function run()
    {
        $date = (new \DateTime('today +20 day'))->format('Y-m-d');
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\MethodCall\DateTimeMethodCallToCarbonRector\Fixture;

class SomeClass
{
    public function run()
    {
        $date = (\Carbon\Carbon::parse('today +20 day'))->format('Y-m-d');
    }
}

?>
