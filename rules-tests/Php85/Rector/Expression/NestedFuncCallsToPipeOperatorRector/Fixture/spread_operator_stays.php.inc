<?php

$arrayOfArrays = [['a'], ['a'], ['b']];
$merged = array_unique(array_merge(...$arrayOfArrays));

?>
-----
<?php

$merged = array_merge(...$arrayOfArrays)
 |> array_unique(...);

?>
