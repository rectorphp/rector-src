<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InnerFunctionToPrivateMethodRector\Fixture;

class SkipStringCallable
{
    public static function getOptions()
    {
        function sortByOrder(array $a, array $b): int
        {
            return strcmp((string) $a['label'], (string) $b['label']);
        }

        $options = [
            ['value' => 'b', 'label' => 'Bravo'],
            ['value' => 'a', 'label' => 'Alpha'],
        ];

        usort($options, 'sortByOrder');
        return $options;
    }
}
