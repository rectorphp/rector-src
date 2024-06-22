<?php

namespace Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Fixture;

use Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\SimpleRoute;

final class MultiplePlaces
{
    #[SimpleRoute()]
    #[SimpleRoute('another')]
    public function subscribe()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Fixture;

use Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\SimpleRoute;

final class MultiplePlaces
{
    #[\Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\NextRoute()]
    #[\Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\NextRoute('another')]
    public function subscribe()
    {
    }
}

?>
