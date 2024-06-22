<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\GetClassToInstanceOfRector\Fixture;

final class SkipStdClass
{
    public function getClassToInstanceOf()
    {
        $someObject = new \stdClass();
        if ('stdClass' === get_class($someObject)) {
            echo 'yes!';
        }

        if (get_class($someObject) !== stdClass::class) {
            echo 'no!';
        }
    }
}
