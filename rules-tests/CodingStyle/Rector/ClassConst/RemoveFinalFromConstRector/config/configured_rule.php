<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\RemoveFinalFromConstRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::FINAL_CLASS_CONSTANTS);

    $rectorConfig->rule(RemoveFinalFromConstRector::class);
};
