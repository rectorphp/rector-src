<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\TimeFuncCallToCarbonRector\Fixture;

class FirstClassCallable
{
    public function run()
    {
        $time = time(...);
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\TimeFuncCallToCarbonRector\Fixture;

class FirstClassCallable
{
    public function run()
    {
        $time = static fn() => \Carbon\Carbon::now()->timestamp;
    }
}

?>
