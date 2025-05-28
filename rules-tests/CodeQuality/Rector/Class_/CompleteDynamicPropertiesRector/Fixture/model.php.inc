<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

abstract class Model
{
    public const PRIMARY_KEY = 'id';

    public function insert(): void
    {
        $this->{static::PRIMARY_KEY} = 1;
    }
}
?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

abstract class Model
{
    public const PRIMARY_KEY = 'id';

    public function insert(): void
    {
        $this->{static::PRIMARY_KEY} = 1;
    }
}
?>
