<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ClassOnObjectGetDebugType;

final class Fixture
{
    public function push($command)
    {
        return is_object($command) ? get_class($command) : $command;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ClassOnObjectGetDebugType;

final class Fixture
{
    public function push($command)
    {
        return is_object($command) ? $command::class : $command;
    }
}

?>
