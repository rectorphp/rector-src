<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

in_array('foo', ['bar'], $strict = true);
in_array('foo', ['bar'], $strict = ! $strict);

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

in_array('foo', ['bar'], $strict = true);
in_array('foo', ['bar'], strict: ! $strict);

?>
