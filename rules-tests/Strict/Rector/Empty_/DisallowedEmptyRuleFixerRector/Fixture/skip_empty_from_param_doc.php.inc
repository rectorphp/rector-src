<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

class SkipEmptyFromParamDoc
{
    /**
     * @param string $value
     */
    public function getLabel($value)
    {
        if (empty($value)) {

        }
    }
}
