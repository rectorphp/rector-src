<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class SkipFromDocblockReturn
{
    public function run()
    {
        return empty($this->getProperty());
    }

    public function run2()
    {
        return ! empty($this->getProperty());
    }

    /**
     * @return string
     */
    public function getProperty()
    {
    }
}
