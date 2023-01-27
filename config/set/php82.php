<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Php82\Rector\FuncCall\Utf8DecodeEncodeToMbConvertEncodingRector;
use Rector\Php82\Rector\New_\FilesystemIteratorSkipDotsRector;
use Rector\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        ReadOnlyClassRector::class,
        Utf8DecodeEncodeToMbConvertEncodingRector::class,
        FilesystemIteratorSkipDotsRector::class,
        AddAllowDynamicPropertiesAttributeRector::class
    ]);
};
