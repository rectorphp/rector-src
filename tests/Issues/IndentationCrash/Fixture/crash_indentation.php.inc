<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture;

for ($i = 0; $i < sizeof($psychiatry); $i++) {
    $psychiatry[$i]["sufficient_cancellation_notice"] = $s->isSufficientNoticeToCancel($psychiatry[$i]) ? 1 : 0;
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture;

$psychiatryCount = count($psychiatry);
for ($i = 0; $i < $psychiatryCount; $i++) {
    $psychiatry[$i]["sufficient_cancellation_notice"] = $s->isSufficientNoticeToCancel($psychiatry[$i]) ? 1 : 0;
}

?>
