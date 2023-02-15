<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

class NullDefinedInVariableFirst
{
    public function getTitle()
    {
        $title = null;
        return trim($title);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

class NullDefinedInVariableFirst
{
    public function getTitle()
    {
        $title = null;
        return trim((string) $title);
    }
}

?>
