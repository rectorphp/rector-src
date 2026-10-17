<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        OldClass::class => NewClass::class,
    ]);

    // both paths are skipped; the rule is skipped once per matching file, so both masks are marked used
    $rectorConfig->skip([
        RenameClassRector::class => ['*skip_used_renames_old_class*', '*skip_unused_no_old_class*'],
    ]);
};
