<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortCallLikeNamedArgsRector\Fixture;

function run($foo = null, $bar = null, $baz = null) {}

run(bar: $bar, foo: $foo);

run($foo, baz: $baz, bar: $bar);

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortCallLikeNamedArgsRector\Fixture;

function run($foo = null, $bar = null, $baz = null) {}

run(foo: $foo, bar: $bar);

run($foo, bar: $bar, baz: $baz);

?>
