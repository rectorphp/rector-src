<?php
$someVar = @suppressed_function($one, $two, $three);
$someVar = @suppressed_function();
?>
-----
<?php
$someVar = \App\Foo::suppressedFunction('suppressed_function', [$one, $two, $three]);
$someVar = \App\Foo::suppressedFunction('suppressed_function');
?>
