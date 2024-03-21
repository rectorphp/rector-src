<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

class SomeClass
{
    public function run(int $number)
    {
        if ($number > 50) {
            return 'yes';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

class SomeClass
{
    public function run(int $number)
    {
        if ($number > 50) {
            return 'yes';
        }
        return null;
    }
}

?>
