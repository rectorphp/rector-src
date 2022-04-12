<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(SetList::PHP_71);
    $rectorConfig->import(LevelSetList::UP_TO_PHP_70);

<<<<<<< HEAD
    // parameter must be defined after import, to override imported param version
    $parameters = $rectorConfig->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);
=======
    // parameter must be defined after import, to override impored param version
    $rectorConfig->phpVersion(PhpVersion::PHP_71);
>>>>>>> 86e20f5821... [DX] Add phpVersion() method to RectorConfig
};
