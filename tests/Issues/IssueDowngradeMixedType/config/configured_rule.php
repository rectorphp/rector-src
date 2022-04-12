<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector;
use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeMixedTypeDeclarationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $services = $containerConfigurator->services();
    $services->set(DowngradeTypedPropertyRector::class);
    $services->set(DowngradeMixedTypeDeclarationRector::class);
};
