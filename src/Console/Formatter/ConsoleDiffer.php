<?php

declare(strict_types=1);

namespace Rector\Core\Console\Formatter;

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

final class ConsoleDiffer
{
    private readonly Differ $differ;

    public function __construct(
        private readonly ColorConsoleDiffFormatter $colorConsoleDiffFormatter
    ) {
        // @see https://github.com/sebastianbergmann/diff#strictunifieddiffoutputbuilder
        // @see https://github.com/sebastianbergmann/diff/compare/4.0.4...5.0.0#diff-251edf56a6344c03fa264a4926b06c2cee43c25f66192d5f39ebee912b7442dc for upgrade
        $unifiedDiffOutputBuilder = new UnifiedDiffOutputBuilder();
        $this->differ = new Differ($unifiedDiffOutputBuilder);
    }

    public function diff(string $old, string $new): string
    {
        $diff = $this->differ->diff($old, $new);
        return $this->colorConsoleDiffFormatter->format($diff);
    }
}
