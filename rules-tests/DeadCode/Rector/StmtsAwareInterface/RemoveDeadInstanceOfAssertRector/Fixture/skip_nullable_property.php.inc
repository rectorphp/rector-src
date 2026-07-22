<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Fixture;

use Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Source\UserRepository;

final class SkipNullableProperty
{
    public function __construct(
        private ?UserRepository $userRepository = null
    ) {
    }

    public function run(): void
    {
        assert($this->userRepository instanceof UserRepository);
    }
}
