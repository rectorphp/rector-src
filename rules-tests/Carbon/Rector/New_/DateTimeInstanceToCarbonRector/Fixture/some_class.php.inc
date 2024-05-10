<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

class SomeClass
{
    public function run()
    {
        $date = new \DateTime();
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

class SomeClass
{
    public function run()
    {
        $date = \Carbon\Carbon::now();
    }
}

?>
