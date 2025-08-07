<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

fn () => in_array('foo', ['bar'], $strict = true);

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

fn () => in_array('foo', ['bar'], strict: true);

?>
