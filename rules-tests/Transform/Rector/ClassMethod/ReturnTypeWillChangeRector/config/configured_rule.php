<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::RETURN_TYPE_WILL_CHANGE_ATTRIBUTE);

    $rectorConfig->rule(ReturnTypeWillChangeRector::class);
};
