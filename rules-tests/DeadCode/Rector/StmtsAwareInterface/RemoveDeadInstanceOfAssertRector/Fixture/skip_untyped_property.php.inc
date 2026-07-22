<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Fixture;

use Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Source\UserRepository;

final class SkipUntypedProperty
{
    private $userRepository;

    public function run(): void
    {
        assert($this->userRepository instanceof UserRepository);
    }
}
