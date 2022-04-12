<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DowngradePhp71\Rector\TryCatch\DowngradePipeToMultiCatchExceptionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::MULTI_EXCEPTION_CATCH - 1);

    $services = $containerConfigurator->services();
    $services->set(DowngradePipeToMultiCatchExceptionRector::class);
};
