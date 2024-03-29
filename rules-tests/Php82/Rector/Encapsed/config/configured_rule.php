<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::DEPRECATE_VARIABLE_IN_STRING_INTERPOLATION);

    $rectorConfig->rule(VariableInStringInterpolationFixerRector::class);
};
