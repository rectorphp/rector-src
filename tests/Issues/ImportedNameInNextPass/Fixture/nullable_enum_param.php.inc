<?php

namespace Rector\Tests\Issues\ImportedNameInNextPass\Fixture;

use Rector\Tests\Issues\ImportedNameInNextPass\Source\Directory;

final class NullableEnumParam
{
    public function run(?Directory $directory): void
    {
        if ($directory === null) {
            return;
        }

        echo $directory->value;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ImportedNameInNextPass\Fixture;

use Rector\Tests\Issues\ImportedNameInNextPass\Source\Directory;

final class NullableEnumParam
{
    public function run(?Directory $directory): void
    {
        if (!$directory instanceof Directory) {
            return;
        }

        echo $directory->value;
    }
}

?>
