<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

/**
 * Both Enum and class in same file on purpose for differentiate use case
 */
enum Status {
    case Foo;
    case Bar;
}

final class Service2 {
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
