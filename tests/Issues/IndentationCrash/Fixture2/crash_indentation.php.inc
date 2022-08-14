<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture2;

$s  = new Sessions();

$psychiatry        = $s->getPtNextSession($patient_id, 3, Util::ROLE_PRESCRIBER);
$psychotherapy     = $s->getPtNextSession($patient_id, 3, Util::ROLE_THERAPIST);

for ($i = 0; $i < sizeof($psychiatry); $i++) {
	$psychiatry[$i]["sufficient_cancellation_notice"] = $s->isSufficientNoticeToCancel($psychiatry[$i]) ? 1 : 0;
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture2;

$s  = new Sessions();

$psychiatry        = $s->getPtNextSession($patient_id, 3, Util::ROLE_PRESCRIBER);
$psychotherapy     = $s->getPtNextSession($patient_id, 3, Util::ROLE_THERAPIST);

foreach ($psychiatry as $i => $singlePsychiatry) {
	$psychiatry[$i]["sufficient_cancellation_notice"] = $s->isSufficientNoticeToCancel($singlePsychiatry) ? 1 : 0;
}

?>
