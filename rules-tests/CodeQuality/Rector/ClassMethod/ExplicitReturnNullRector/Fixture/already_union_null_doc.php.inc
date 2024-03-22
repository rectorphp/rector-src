<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

class AlreadyUnionNull
{
    /**
     * @return ?string
     */
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

class AlreadyUnionNull
{
    /**
     * @return ?string
     */
    public function run(int $number)
    {
        if ($number > 50) {
            return 'yes';
        }
        return null;
    }
}

?>
