<?php

namespace Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchMethodCallReturnTypeRector\Fixture;

use Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchMethodCallReturnTypeRector\Source\RuleMetadata;

class SkipMetadataMassNoun
{
    public function run()
    {
        $array = [];
        foreach ($this->findCore() as $ruleMetadata) {
            $array[] = $ruleMetadata;
        }
    }

    /**
     * @return RuleMetadata[]
     */
    public function findCore(): array
    {
        return [];
    }
}
