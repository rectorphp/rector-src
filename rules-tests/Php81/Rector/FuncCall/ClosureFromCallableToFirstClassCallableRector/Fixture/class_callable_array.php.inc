<?php

Closure::fromCallable(['SomeClass', 'staticMethod']);
Closure::fromCallable(['Foo\SomeClass', 'staticMethod']);

?>
-----
<?php

SomeClass::staticMethod(...);
\Foo\SomeClass::staticMethod(...);

?>
