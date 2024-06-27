<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Immutable;

final class PropertyWithAttribute
{
    #[Immutable]
    public float $latitude;

    #[Immutable]
    #[Deprecated]
    public float $longiture;

    public function __construct(float $latitude, float $longiture)
    {
        $this->latitude = $latitude;
        $this->longiture = $longiture;

    }
}
?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Immutable;

final class PropertyWithAttribute
{
    public function __construct(#[Immutable]
    public float $latitude, #[Immutable]
    #[Deprecated]
    public float $longiture)
    {
    }
}
?>
