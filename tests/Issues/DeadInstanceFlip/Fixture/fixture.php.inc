<?php

namespace Rector\Core\Tests\Issues\DeadInstanceFlip\Fixture;

use Rector\Core\Tests\Issues\DeadInstanceFlip\Source\SomeEvent;

final class Fixture
{
    public function process(SomeEvent $someEvent)
    {
        if (null !== $class = $someEvent->getSomeType()) {
            $class->result;
        }
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\DeadInstanceFlip\Fixture;

use Rector\Core\Tests\Issues\DeadInstanceFlip\Source\SomeEvent;

final class Fixture
{
    public function process(SomeEvent $someEvent)
    {
        $class = $someEvent->getSomeType();
        $class->result;
    }
}

?>
