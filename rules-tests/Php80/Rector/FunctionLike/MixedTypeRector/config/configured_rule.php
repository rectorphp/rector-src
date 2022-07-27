<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php80\Rector\FunctionLike\MixedTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(MixedTypeRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
