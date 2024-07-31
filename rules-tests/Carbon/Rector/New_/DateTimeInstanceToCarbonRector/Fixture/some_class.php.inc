<?php

namespace Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\Fixture;

class SomeClass
{
    public function run()
    {
        $date = new \DateTime();
        $newDate = new \DateTime($date);
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
        $newDate = new \Carbon\Carbon($date);
    }
}

?>
