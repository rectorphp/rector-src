<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector\Fixture;

/**
 * @var Rechnung[]            $Rechnung
 */

if ($Rechnung && count($Rechnung) > 0) {

}


?>
