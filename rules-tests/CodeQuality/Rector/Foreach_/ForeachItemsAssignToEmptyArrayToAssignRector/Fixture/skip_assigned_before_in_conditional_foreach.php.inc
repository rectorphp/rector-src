<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

final class SkipAssignedBeforeInConditionalForeach
{
    public function run($data)
    {
        $list = [];
        $groupList = [];

        foreach ($data->getItems() as $item) {
            if ($item->hasGroup()) {
                $groupList[] = ['id' => $item->getGroupId()];
            } else {
                $list[] = ['id' => $item->getId()];
            }
        }

        foreach ($groupList as $group) {
            $list[] = $group;
        }

        return $list;
    }
}
