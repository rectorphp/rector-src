<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortCallLikeNamedArgsRector\Fixture;

function variadic($title, ...$params) {}

variadic(foo: $foo, title: $title, bar: $bar);

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortCallLikeNamedArgsRector\Fixture;

function variadic($title, ...$params) {}

variadic(title: $title, foo: $foo, bar: $bar);

?>
