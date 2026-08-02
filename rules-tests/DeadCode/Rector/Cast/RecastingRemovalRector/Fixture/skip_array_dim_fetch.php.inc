<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

class SkipArrayDimFetch
{
    public function run()
    {
        $list = ['id' => 1, 'name' => 'Tom'];

        $id = (int) $list['id'];
        $name = (string) $list['name'];

        return [$id, $name];
    }
}
