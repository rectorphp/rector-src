<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Source\EliteManager;

final class PromotedPropertyRenameFetch
{
    public function __construct(
        private EliteManager $eventManager
    )
    {
    }

    public function run()
    {
        return $this->eventManager;
    }
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Source\EliteManager;

final class PromotedPropertyRenameFetch
{
    public function __construct(
        private EliteManager $eliteManager
    )
    {
    }

    public function run()
    {
        return $this->eliteManager;
    }
}

?>
