<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

use Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Source\SomeClass;

SomeClass::staticMethod('foo', $b = 'value');

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Fixture;

use Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\Source\SomeClass;

SomeClass::staticMethod('foo', b: 'value');

?>
