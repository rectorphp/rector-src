<?php

use bridge\workshop\viewmodel\AppointmentFormVM;
use bridge\workshop\business\LocationVO;

/** @var AppointmentFormVM $vm */

/** @var LocationVO|null $testLocation */
$testLocation = null;
foreach ($vm->locations as $location) {
    if ($location->isTestLocation()) {
        $testLocation = $location;
        break;
    }
}
