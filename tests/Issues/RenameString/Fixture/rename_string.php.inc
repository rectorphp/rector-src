<?php

namespace Rector\Tests\Issues\RenameString\Fixture;

final class RenameString
{
    public function run($variable)
    {
        return is_a(
            $variable,
            'Rector\Tests\Issues\RenameString\Fixture\RenameString',
            true
        );
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\RenameString\Fixture;

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
