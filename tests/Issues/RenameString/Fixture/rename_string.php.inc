<?php

namespace Rector\Tests\Issues\DoubleRun\Fixture;

final class RenameString
{
    public function run($variable)
    {
        return is_a(
            $variable,
            'Rector\Core\Tests\Issues\DoubleRun\Fixture\RenameString',
            true
        );
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\DoubleRun\Fixture;

final class RenameString
{
    public function run($variable)
    {
        return is_a(
            $variable,
            'new test',
            true
        );
    }
}

?>
