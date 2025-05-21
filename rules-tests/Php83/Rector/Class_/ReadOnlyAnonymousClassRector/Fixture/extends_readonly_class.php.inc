<?php

namespace newRector\Tests\Php83\Rector\Class_\ReadOnlyAnonymousClassRector\Fixture;

use Rector\Tests\Php83\Rector\Class_\ReadOnlyAnonymousClassRector\Source\ParentAlreadyReadonly;

new class() extends ParentAlreadyReadonly
{
   private readonly string $name = 'test';
};

?>
-----
<?php

namespace newRector\Tests\Php83\Rector\Class_\ReadOnlyAnonymousClassRector\Fixture;

use Rector\Tests\Php83\Rector\Class_\ReadOnlyAnonymousClassRector\Source\ParentAlreadyReadonly;

new readonly class() extends ParentAlreadyReadonly
{
   private string $name = 'test';
};

?>