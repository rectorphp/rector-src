<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipByDocblockParamDefinition
{
    /**
     * @param array<string, array|string>|string $fields
     */
    public function run($fields)
    {
        $data = ['init'];

        if (is_array($fields)) {
            foreach ($fields as $name => $attributes) {
                if (is_string($attributes)) {
                    continue;
                }

                if (is_array($attributes)) {
                    $data = (array_merge($data, [$name => $attributes]));
                }
            }
        }

        return $this;
    }
}
