<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassLike\RemoveTypedPropertyNonMockDocblockRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveTypedPropertyNonMockDocblockRector::class);
};
