<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector\Fixture;

final class SkipAppendVariableValueBeforeIf
{
	public function getTravelString(bool $shorten, int $range, string $metric, float $distance): string
	{
		$label = ($shorten) ? ($metric === 'time' ? ' hrs' : ' mi') : ($metric === 'time' ? ' hours' : ' miles');
		$range = ($metric === 'time') ? round($range / 60, 1) : $range;

		$travels = ($shorten) ? '' : 'up to ';
 		$travels .= $range . $label;

		if ($distance > 3000) {
			$travels = ($shorten) ? 'any' : 'nationwide';
		}

		return $travels;
	}
}

?>
