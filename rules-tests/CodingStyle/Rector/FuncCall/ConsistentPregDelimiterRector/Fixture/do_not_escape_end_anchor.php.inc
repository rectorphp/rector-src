<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ConsistentPregDelimiterRector\Fixture;

class DoNotEscapeEndAnchor
{
    public function run($value)
    {
        preg_match("/^value$/", $value);
        preg_match("/^value$/u", $value);
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
        preg_match("#^value$#u", $value);
    }
}

?>
