<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\Core\ValueObject\PhpVersion;
use Rector\Core\ValueObject\PolyfillPackage;
use Rector\Php80\Rector\Identical\StrStartsWithRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(StrStartsWithRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_74);

    $rectorConfig->polyfillPackages([PolyfillPackage::PHP_80]);
};
