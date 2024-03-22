<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

class WithUnionVoidDoc
{
    /**
     * @return string|void
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

class WithUnionVoidDoc
{
    /**
     * @return string|null
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
