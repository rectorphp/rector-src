<?php

namespace newRector\Tests\Php83\Rector\Class_\ReadOnlyAnonymousClassRector\Fixture;

new class()
{
   private readonly string $name = 'test';
};

?>
-----
<?php

namespace newRector\Tests\Php83\Rector\Class_\ReadOnlyAnonymousClassRector\Fixture;

new readonly class()
{
   private string $name = 'test';
};

?>
