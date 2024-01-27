<?php

declare(strict_types=1);

namespace Rector\Differ;

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;

final readonly class DefaultDiffer
{
    private Differ $differ;

    public function __construct()
    {
        $strictUnifiedDiffOutputBuilder = new StrictUnifiedDiffOutputBuilder([
            'fromFile' => 'Original',
            'toFile' => 'New',
        ]);
        $this->differ = new Differ($strictUnifiedDiffOutputBuilder);
    }

    public function diff(string $old, string $new): string
    {
        $old = str_replace("\r\n", "\n", $old);
        $new = str_replace("\r\n", "\n", $new);

        if ($old === $new) {
            return '';
        }

        return $this->differ->diff($old, $new);
    }
}
