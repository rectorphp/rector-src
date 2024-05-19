<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class SomeClass
{
    public function run()
    {
        $time = time();
    }
}

?>
-----
<?php

namespace Rector\Tests\Carbon\Rector\FuncCall\DateFuncCallToCarbonRector\Fixture;

class SomeClass
{
    public function run()
    {
        $time = \Carbon\Carbon::now()->timestamp;
    }
}

?>
