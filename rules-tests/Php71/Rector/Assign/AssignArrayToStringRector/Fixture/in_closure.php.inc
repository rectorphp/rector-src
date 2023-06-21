<?php

namespace Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\Fixture;

class InClosure
{
    public function fun()
    {
        function () {
            $array = [1, 2, 3];

            $someString = '';
            foreach ($array as $item) {
                $someString[] = $item;
            }
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\Fixture;

class InClosure
{
    public function fun()
    {
        function () {
            $array = [1, 2, 3];

            $someString = [];
            foreach ($array as $item) {
                $someString[] = $item;
            }
        };
    }
}

?>
