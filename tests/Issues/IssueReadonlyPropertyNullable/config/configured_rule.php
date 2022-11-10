<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Restoration\Rector\Property\MakeTypedPropertyNullableIfCheckedRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReadOnlyPropertyRector::class);
    $rectorConfig->rule(MakeTypedPropertyNullableIfCheckedRector::class);
};
