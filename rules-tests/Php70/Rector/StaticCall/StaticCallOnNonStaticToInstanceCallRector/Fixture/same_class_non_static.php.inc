<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class SameClassNonStatic
{
    public function run()
    {
        return SameClassNonStatic::doWork();
    }

    public function doWork()
    {
        return 'work done';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class SameClassNonStatic
{
    public function run()
    {
        return $this->doWork();
    }

    public function doWork()
    {
        return 'work done';
    }
}

?>
