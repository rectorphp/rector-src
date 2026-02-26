<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source;

use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * @property \Carbon\CarbonInterface|null $deleted_at
 */
class MergedImportError
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

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source;

use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * @property CarbonInterface|null $deleted_at
 */
class MergedImportError
{
    public function demoFunction(): array
    {
        return [Carbon::parse('2026-01-01 11:16:01')];
    }
}

?>
