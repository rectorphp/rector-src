<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Identical\StrStartsWithRector;
use Rector\ValueObject\PhpVersion;
use Rector\ValueObject\PolyfillPackage;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(StrStartsWithRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_74);

    $rectorConfig->polyfillPackages([PolyfillPackage::PHP_80]);
};
