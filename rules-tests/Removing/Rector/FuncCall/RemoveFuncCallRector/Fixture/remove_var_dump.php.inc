<?php

namespace Rector\Tests\Removing\Rector\FuncCall\RemoveFuncCallRector\Fixture;

class SomeAction
{
    public function run()
    {
        $x = 'something';
        var_dump($x);
        return $x;
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
        $x = 'something';
        return $x;
    }
}

?>
