<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ConsistentPregDelimiterRector\Fixture;

class DoNotEscapeEndAnchor
{
    public function run($value)
    {
        preg_match("/^value$/", $value);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ConsistentPregDelimiterRector\Fixture;

class DoNotEscapeEndAnchor
{
    public function run($value)
    {
        preg_match("#^value$#", $value);
    }
}

?>
