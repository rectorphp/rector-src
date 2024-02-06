<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

use stdClass;

class SkipClosureCall
{
    public function getPrivateProperty()
    {
        $randomGenerator = new stdClass();

        (function () {
            return $this->uuidV4Generator;
        })->call($randomGenerator);
    }
}
