<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture;

$s  = new Sessions();

$psychiatry        = $s->getPtNextSession($patient_id, 3, Util::ROLE_PRESCRIBER);
$psychotherapy     = $s->getPtNextSession($patient_id, 3, Util::ROLE_THERAPIST);

for ($i = 0; $i < sizeof($psychiatry); $i++) {
	$psychiatry[$i]["sufficient_cancellation_notice"] = $s->isSufficientNoticeToCancel($psychiatry[$i]) ? 1 : 0;
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture;

$s  = new Sessions();

$psychiatry        = $s->getPtNextSession($patient_id, 3, Util::ROLE_PRESCRIBER);
$psychotherapy     = $s->getPtNextSession($patient_id, 3, Util::ROLE_THERAPIST);

$psychiatryCount = count($psychiatry);
for ($i = 0; $i < $psychiatryCount; $i++) {
    $psychiatry[$i]["sufficient_cancellation_notice"] = $s->isSufficientNoticeToCancel($psychiatry[$i]) ? 1 : 0;
}

?>
