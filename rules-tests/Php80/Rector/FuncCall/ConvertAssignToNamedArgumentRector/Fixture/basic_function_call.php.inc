<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

function basicFunctionCall()
{
    in_array('foo', $array, $strict = true);
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

function basicFunctionCall()
{
    in_array('foo', $array, strict: true);
}

?>