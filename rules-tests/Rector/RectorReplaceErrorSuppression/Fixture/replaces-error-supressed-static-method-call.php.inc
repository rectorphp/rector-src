<?php
$someVar = @ClassName::someMethod($one, $two, $three);
$someVar = @ClassName::someMethod();
?>
-----
<?php
$someVar = \App\Foo::suppressedFunction(['ClassName', 'someMethod'], [$one, $two, $three]);
$someVar = \App\Foo::suppressedFunction(['ClassName', 'someMethod']);
?>
