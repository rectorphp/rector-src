<?php

namespace Rector\Tests\Php85\Rector\StmtsAwareInterface\NestedToPipeOperatorRector\Fixture;

$result = trim(strtoupper("Hello World"));
?>
-----
<?php

namespace Rector\Tests\Php85\Rector\StmtsAwareInterface\NestedToPipeOperatorRector\Fixture;

$result = strtoupper("Hello World") |> trim(...);
?>