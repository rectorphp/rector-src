<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

/**
 * Use both Enum and usage in same file on purpose to reproduce the error
 */
enum Status {
    case Foo;
    case Bar;
}

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
