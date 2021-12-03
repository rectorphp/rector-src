<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\IntersectionString\Fixture;

final class SkipStringIntersection
{
    public function execute()
    {
        $value = value() . __FILE__ ;
        $this->run($value);
    }

    private function run($value)
    {
    }
}
