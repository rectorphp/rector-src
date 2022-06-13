<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

class IsCountable
{
    public function run($values)
    {
        $valueCount = count($values);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\FuncCall\CountOnNullRector\Fixture;

class IsCountable
{
    public function run($values)
    {
        $valueCount = is_countable($values) ? count($values) : (is_null($values) ? 0 : 1);
    }
}

?>
