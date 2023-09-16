<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\Source\stdClass;

class SkipPhpDoc
{
    public function run()
    {
        /** @var stdClass|null $stdClass */
        $stdClass = $this->getStdClass();
        if ($stdClass === null) {
            return;
        }
    }

    public function getStdClass()
    {
        return new \stdClass();
    }
}

?>
