<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

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
