<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_74);

    $services = $containerConfigurator->services();
    $services->set(RemoveParentCallWithoutParentRector::class);
};
