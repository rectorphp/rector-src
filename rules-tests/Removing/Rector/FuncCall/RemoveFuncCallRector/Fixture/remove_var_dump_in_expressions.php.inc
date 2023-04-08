<?php

namespace Rector\Tests\Removing\Rector\FuncCall\RemoveFuncCallRector\Fixture;

class SomeAction
{
    public function run()
    {
        if (var_dump('something')) {
            return true;
        }

        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\FuncCall\RemoveFuncCallRector\Fixture;

class SomeAction
{
    public function run()
    {
        return false;
    }
}

?>
