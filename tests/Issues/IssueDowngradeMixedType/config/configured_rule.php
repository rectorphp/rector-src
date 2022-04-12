<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector;
use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeMixedTypeDeclarationRector;

return static function (RectorConfig $rectorConfig): void {
    $parameters = $rectorConfig->parameters();
    $rectorConfig->importNames();

    $services = $rectorConfig->services();
    $services->set(DowngradeTypedPropertyRector::class);
    $services->set(DowngradeMixedTypeDeclarationRector::class);
};
