<?php

namespace Rector\Tests\CodingStyle\Rector\Foreach_\MultiDimensionalArrayToArrayDestructRector\Fixture;

final class SkipForeachWithNonMultiDimensionalArray
{
    /**
     * @param array{id: int, name: string} $userFields
     */
    public function run6(array $userFields)
    {
        $translations = [
            'id' => 'ID',
            'name' => 'Name',
        ];

        foreach ($userFields as $key => $userField) {
            echo sprintf('%s: %s', $translations[$key], $userField);
        }
    }
}
