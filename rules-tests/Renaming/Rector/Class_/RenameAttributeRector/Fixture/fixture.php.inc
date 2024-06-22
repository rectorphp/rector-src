<?php

namespace Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Fixture;

use Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\SimpleRoute;

final class SomeClass
{
    #[SimpleRoute()]
    public function subscribe()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Fixture;

use Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\SimpleRoute;

final class SomeClass
{
    #[\Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\NextRoute()]
    public function subscribe()
    {
    }
}

?>
