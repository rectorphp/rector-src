<?php

declare(strict_types=1);

namespace Rector\Core\Differ;

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;

final class DefaultDiffer
{
    private readonly Differ $differ;

    private readonly bool $isWindows;

    public function __construct()
    {
        $strictUnifiedDiffOutputBuilder = new StrictUnifiedDiffOutputBuilder([
            'fromFile' => 'Original',
            'toFile' => 'New',
        ]);
        $this->differ = new Differ($strictUnifiedDiffOutputBuilder);
        $this->isWindows = strncasecmp(PHP_OS, 'WIN', 3) === 0;
    }

    public function diff(string $old, string $new): string
    {
        if ($old === $new) {
            return '';
        }

        if ($this->isWindows) {
            $old = str_replace("\r\n", "\n", $old);
            $new = str_replace("\r\n", "\n", $new);
        }

        return $this->differ->diff($old, $new);
    }
}
