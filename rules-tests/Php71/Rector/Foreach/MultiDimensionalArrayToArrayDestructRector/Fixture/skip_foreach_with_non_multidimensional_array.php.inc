<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

final class SkipForeachWithNonMultiDimensionalArray
{
    /**
     * @param array{id: int, name: string} $userFields
     */
    public function run(array $userFields)
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
