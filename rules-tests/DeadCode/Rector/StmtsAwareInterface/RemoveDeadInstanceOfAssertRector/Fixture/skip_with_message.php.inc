<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Fixture;

use Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Source\UserRepository;

final class SkipWithMessage
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function run(): void
    {
        assert($this->userRepository instanceof UserRepository, 'must be set');
    }
}
