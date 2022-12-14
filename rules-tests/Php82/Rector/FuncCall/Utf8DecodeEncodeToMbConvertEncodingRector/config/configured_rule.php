<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\FuncCall\Utf8DecodeEncodeToMbConvertEncodingRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(Utf8DecodeEncodeToMbConvertEncodingRector::class);
};
