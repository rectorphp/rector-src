<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

class OnFunction
{
    public function run(int $number)
    {
        function test(int $number)
        {
            if ($number > 50) {
                return 'yes';
            }
        }

        return test($number);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

class OnFunction
{
    public function run(int $number)
    {
        function test(int $number)
        {
            if ($number > 50) {
                return 'yes';
            }
            return null;
        }

        return test($number);
    }
}

?>
