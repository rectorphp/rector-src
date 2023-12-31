<?php

namespace Rector\Tests\Issues\DeadInstanceFlip\Fixture;

use Rector\Tests\Issues\DeadInstanceFlip\Source\SomeEvent;

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

namespace Rector\Tests\Issues\DeadInstanceFlip\Fixture;

use Rector\Tests\Issues\DeadInstanceFlip\Source\SomeEvent;

final class Fixture
{
    public function process(SomeEvent $someEvent)
    {
        $class = $someEvent->getSomeType();
        $class->result;
    }
}

?>
