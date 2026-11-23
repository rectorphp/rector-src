<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture;

use Closure;

function objectCallableArray(\stdClass $obj)
{
    return Closure::fromCallable([$obj, 'method']);
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture;

use Closure;

function objectCallableArray(\stdClass $obj)
{
    return $obj->method(...);
}

?>
