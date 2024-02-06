<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

class SkipDynamicProperties
{
    public function unserialize($serialized)
    {
        $data = \unserialize($serialized);
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}
