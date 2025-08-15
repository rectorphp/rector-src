<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(NarrowTooWideReturnTypeRector::class);
    $rectorConfig->phpVersion(PhpVersionFeature::NEVER_TYPE);
};
