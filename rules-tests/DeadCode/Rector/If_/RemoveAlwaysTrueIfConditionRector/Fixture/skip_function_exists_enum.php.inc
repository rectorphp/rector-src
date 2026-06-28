<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipFunctionExistsEnum
{
    /**
     * @param array<string> $enums
     */
    public function expand(array $enums): array
    {
        $cases = [];
        foreach ($enums as $enum) {
            if (is_string($enum) && function_exists('enum_exists') && enum_exists($enum)) {
                foreach ($enum::cases() as $case) {
                    $cases[] = $case;
                }
            } else {
                $cases[] = $enum;
            }
        }

        return $cases;
    }
}
