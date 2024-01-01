<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\FunctionLike\MixedTypeRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(MixedTypeRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
