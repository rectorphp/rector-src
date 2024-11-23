<?php

declare(strict_types=1);

namespace Rector\Console\Formatter;

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;

final readonly class ConsoleDiffer
{
    private Differ $differ;

    public function __construct(
        private ColorConsoleDiffFormatter $colorConsoleDiffFormatter
    ) {
        $strictUnifiedDiffOutputBuilder = new StrictUnifiedDiffOutputBuilder([
            'fromFile' => 'Original',
            'toFile' => 'New',
        ]);

        $this->differ = new Differ($strictUnifiedDiffOutputBuilder);
    }

    public function diff(string $old, string $new): string
    {
        $diff = $this->differ->diff($old, $new);
        return $this->colorConsoleDiffFormatter->format($diff);
    }
}
