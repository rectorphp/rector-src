<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture;

$psychiatry = [1, 2, 3];

for ($i = 0; $i < sizeof($psychiatry); $i++) {
    $psychiatry[$i]["sufficient_cancellation_notice"] = mt_rand(1, 3) ? 1 : 0;
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture;

$psychiatry = [1, 2, 3];
$psychiatryCount = count($psychiatry);

for ($i = 0; $i < $psychiatryCount; $i++) {
    $psychiatry[$i]["sufficient_cancellation_notice"] = mt_rand(1, 3) ? 1 : 0;
}

?>
