<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture;

use Closure;

Closure::fromCallable([$obj, 'method']);

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture;

use Closure;

$obj->method(...);

?>
