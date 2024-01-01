<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\ClassConst\FinalizePublicClassConstantRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FinalizePublicClassConstantRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::FINAL_CLASS_CONSTANTS);
};
