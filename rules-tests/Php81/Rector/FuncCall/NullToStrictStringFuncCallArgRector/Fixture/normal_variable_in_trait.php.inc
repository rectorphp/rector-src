<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

trait NormalVariableInTrait
{
    public function getTitle($title)
    {
        return trim($title);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

trait NormalVariableInTrait
{
    public function getTitle($title)
    {
        return trim((string) $title);
    }
}

?>
