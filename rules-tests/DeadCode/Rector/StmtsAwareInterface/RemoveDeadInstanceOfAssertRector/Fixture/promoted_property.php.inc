<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Fixture;

use Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Source\UserRepository;

final class PromotedProperty
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function run(): void
    {
        assert($this->userRepository instanceof UserRepository);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Fixture;

use Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Source\UserRepository;

final class PromotedProperty
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function run(): void
    {
    }
}

?>
