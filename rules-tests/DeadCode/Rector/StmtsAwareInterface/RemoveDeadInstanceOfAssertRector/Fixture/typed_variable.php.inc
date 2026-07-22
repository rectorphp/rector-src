<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Fixture;

use Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Source\UserRepository;

function typedVariable(UserRepository $userRepository): void
{
    assert($userRepository instanceof UserRepository);
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Fixture;

use Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Source\UserRepository;

function typedVariable(UserRepository $userRepository): void
{
}

?>
