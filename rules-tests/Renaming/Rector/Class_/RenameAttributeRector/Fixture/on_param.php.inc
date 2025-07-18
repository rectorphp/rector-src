<?php

namespace Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Fixture;

use Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\SimpleParamAttribute;

final class OnParam
{
    public function __construct(
        #[SimpleParamAttribute]
        private ServiceA $serviceA,
    )
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Fixture;

use Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\SimpleParamAttribute;

final class OnParam
{
    public function __construct(
        #[\Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\NextParamAttribute]
        private ServiceA $serviceA,
    )
    {
    }
}

?>
