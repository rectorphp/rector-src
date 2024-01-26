<?php

declare(strict_types=1);

namespace Rector\Console\Formatter;

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

final readonly class ConsoleDiffer
{
    private Differ $differ;
    private readonly bool $isWindows;

    public function __construct(
        private ColorConsoleDiffFormatter $colorConsoleDiffFormatter
    ) {
        // @see https://github.com/sebastianbergmann/diff#strictunifieddiffoutputbuilder
        // @see https://github.com/sebastianbergmann/diff/compare/4.0.4...5.0.0#diff-251edf56a6344c03fa264a4926b06c2cee43c25f66192d5f39ebee912b7442dc for upgrade
        $unifiedDiffOutputBuilder = new UnifiedDiffOutputBuilder();
        $this->differ = new Differ($unifiedDiffOutputBuilder);
        $this->isWindows = strncasecmp(PHP_OS, 'WIN', 3) === 0;
    }

    public function diff(string $old, string $new): string
    {
        // avoid Strings contain different line endings warning
        if ($this->isWindows) {
            $old = str_replace("\r\n", "\n", $old);
            $new = str_replace("\r\n", "\n", $new);
        }

        $diff = $this->differ->diff($old, $new);
        return $this->colorConsoleDiffFormatter->format($diff);
    }
}
