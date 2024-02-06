<?php

namespace Rector\Tests\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector\Fixture;

final class SkipInnerClass
{
    public function run()
    {
        $box = new class () {
            public $logged;

            public function hold(string $value)
            {
                $this->logged = $value;
            }
        };

        echo $box->logged;
    }
}
