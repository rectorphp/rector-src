<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Illuminate\Support\Carbon;
use Carbon\CarbonInterface;

/**
 * @property \Carbon\CarbonInterface|null $deleted_at
 */
class NotInUseFqcnDocblock
{
    public function demoFunction(): array
    {
        return [Carbon::parse('2026-01-01 11:16:01')];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Illuminate\Support\Carbon;
use Carbon\CarbonInterface;

/**
 * @property CarbonInterface|null $deleted_at
 */
class NotInUseFqcnDocblock
{
    public function demoFunction(): array
    {
        return [Carbon::parse('2026-01-01 11:16:01')];
    }
}

?>
