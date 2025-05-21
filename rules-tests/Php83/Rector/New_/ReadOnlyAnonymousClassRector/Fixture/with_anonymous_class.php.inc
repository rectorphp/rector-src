<?php

namespace newRector\Tests\Php83\Rector\New_\ReadOnlyClassRector\Fixture;

new class()
{
   private readonly string $name = 'test';
};

?>
-----
<?php

namespace newRector\Tests\Php83\Rector\New_\ReadOnlyClassRector\Fixture;

new readonly class()
{
   private string $name = 'test';
};

?>