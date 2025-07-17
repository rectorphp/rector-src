<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture;

use SomeNamespace\Foo;
use Closure;

Closure::fromCallable(['SomeClass', 'staticMethod']);
Closure::fromCallable(['Foo\SomeClass', 'staticMethod']);

Closure::fromCallable([SomeClass::class, 'staticMethod']);
Closure::fromCallable([Foo\SomeClass::class, 'staticMethod']);

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture;

use SomeNamespace\Foo;
use Closure;

\SomeClass::staticMethod(...);
\Foo\SomeClass::staticMethod(...);

\Rector\Tests\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\Fixture\SomeClass::staticMethod(...);
\SomeNamespace\Foo\SomeClass::staticMethod(...);

?>
