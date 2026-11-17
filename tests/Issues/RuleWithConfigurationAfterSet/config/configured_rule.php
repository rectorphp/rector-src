<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::PHP_VERSION_BASED_SET]);
    $rectorConfig->phpVersion(PhpVersion::PHP_81);

    $rectorConfig->ruleWithConfiguration(StringClassNameToClassConstantRector::class, ['Doctrine\*']);
};
