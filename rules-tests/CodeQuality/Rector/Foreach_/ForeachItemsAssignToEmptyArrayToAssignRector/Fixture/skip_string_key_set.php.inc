<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Source\Identity;

class SkipStringKeySet
{
    public function run()
    {
        $peopleWithStringKey = [];
        $peopleWithIntKey = [];

        foreach ([new Identity()] as $identity) {
            $peopleWithStringKey[$identity->getName()] = $identity;
        }

        foreach ($peopleWithStringKey as $newIdentity) {
            $peopleWithIntKey[] = $newIdentity;
        }

        var_dump($peopleWithStringKey);
        var_dump($peopleWithIntKey);
    }
}
