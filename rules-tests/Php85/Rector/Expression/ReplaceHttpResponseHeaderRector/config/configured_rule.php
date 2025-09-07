<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php85\Rector\Expression\ReplaceHttpResponseHeaderRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReplaceHttpResponseHeaderRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_85);
};
