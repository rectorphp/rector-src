<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\FuncCall\Utf8DecodeEncodeToMbConvertEncodingRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::DEPRECATE_UTF8_DECODE_ENCODE_FUNCTION);

    $rectorConfig->rule(Utf8DecodeEncodeToMbConvertEncodingRector::class);
};
