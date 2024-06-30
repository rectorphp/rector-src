<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::NULLSAFE_OPERATOR);
    $rectorConfig->rule(CleanupUnneededNullsafeOperatorRector::class);
};
