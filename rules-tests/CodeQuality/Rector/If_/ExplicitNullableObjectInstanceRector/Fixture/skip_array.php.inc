<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use stdClass;

class SkipArray
{
    public function run()
    {
        /** @var stdClass[]|null $stdClass */
        $stdClass = $this->getStdClass();
        if ($stdClass === null) {
            return;
        }
    }
}
