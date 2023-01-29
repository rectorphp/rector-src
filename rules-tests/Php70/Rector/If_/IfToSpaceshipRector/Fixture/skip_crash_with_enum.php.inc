<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

use Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Source\Status;

final class Service {
    public function status(int $param): Status {
        /**
         * These pass:
         *
         * - if ($param)
         * - if (true)
         */
        if ($param === 1) {
            return Status::Foo;
        }

        return Status::Bar;
    }
}
