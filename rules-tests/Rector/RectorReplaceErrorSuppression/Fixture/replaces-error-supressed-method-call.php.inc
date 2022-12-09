<?php
$someVar = @$class->someMethod($one, $two, $three);
$someVar = @$class->someMethod();
?>
-----
<?php
$someVar = \App\Foo::suppressedFunction([$class, 'someMethod'], [$one, $two, $three]);
$someVar = \App\Foo::suppressedFunction([$class, 'someMethod']);
?>
