<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\Ternary\TernaryToNullCoalescingRector;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Some\Exception' => 'Some\Target\Exception',
    ]);
    $rectorConfig->rule(TernaryToNullCoalescingRector::class);
};
