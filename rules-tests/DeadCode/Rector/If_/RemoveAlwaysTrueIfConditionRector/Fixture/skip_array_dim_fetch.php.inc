<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

$changes = [];
foreach (toAdd() as $add) {
    $changes[$add['id']]['add'][] = 1;
}
foreach (toRem() as $del) {
    $changes[$del['id']]['del'][] = 2;
}
foreach ($changes as $changeSet) {
    if (isset($changeSet['del'])) {
        echo 1;
    }
    if (isset($changeSet['add'])) {
        echo 2;
    }
}

/**
 * @return array{array{id: int}}|array{}
 */
function toAdd(): array
{
	if (rand(0,1)) {
		return [];
	}

	return [
		0 => [
			'id' => 1,
		],
	];
}

/**
 * @return array{array{id: int}}|array{}
 */
function toRem(): array
{
	if (rand(0,1)) {
		return [];
	}

	return [
		0 => [
			'id' => 1,
		]
	];
}