<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

class SkipValueExtraction
{
    public function check($type)
    {
        $data = [
            ['type' => 'foo'],
        ];

        foreach ($data as ['type' => $currentType]) {
            if ($currentType === $type) {
                return true;
            }
        }

        return false;
    }
}
