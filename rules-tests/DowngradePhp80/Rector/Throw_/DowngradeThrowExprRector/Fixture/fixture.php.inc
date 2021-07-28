<?php

namespace Rector\Tests\DowngradePhp80\Rector\Throw_\DowngradeThrowExprRector\Fixture;

use RuntimeException;

class Fixture
{
    public function run()
    {
        $id = $somethingNonexistent ?? throw new RuntimeException();
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\Throw_\DowngradeThrowExprRector\Fixture;

use RuntimeException;

class Fixture
{
    public function run()
    {
        if (!isset($somethingNonexistent)) {
            throw new RuntimeException();
        }
        $id = $somethingNonexistent;
    }
}

?>
