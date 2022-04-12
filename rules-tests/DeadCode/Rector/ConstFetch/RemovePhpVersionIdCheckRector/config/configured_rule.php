<?php

declare(strict_types=1);

use Rector\Core\ValueObject\PhpVersion;
use Rector\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemovePhpVersionIdCheckRector::class)
        ->configure([PhpVersion::PHP_80]);
};
