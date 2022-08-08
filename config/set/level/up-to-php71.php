<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::PHP_71, SetList::UP_TO_PHP_70]);

    // parameter must be defined after import, to override imported param version
    $rectorConfig->phpVersion(PhpVersion::PHP_71);
};
