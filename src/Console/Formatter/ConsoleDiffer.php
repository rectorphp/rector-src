<?php

declare(strict_types=1);

namespace Rector\Core\Console\Formatter;

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

final class ConsoleDiffer
{
    private readonly Differ $differ;

    public function __construct(
        private readonly ColorConsoleDiffFormatter $colorConsoleDiffFormatter
    ) {
        // @see https://github.com/sebastianbergmann/diff#strictunifieddiffoutputbuilder
        $unifiedDiffOutputBuilder = new UnifiedDiffOutputBuilder();
        $this->differ = new Differ($unifiedDiffOutputBuilder);
    }

    public function diff(string $old, string $new): string
    {
        $diff = $this->differ->diff($old, $new);
        return $this->colorConsoleDiffFormatter->format($diff);
    }
}
