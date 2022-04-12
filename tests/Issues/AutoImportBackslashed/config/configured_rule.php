<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\Core\Configuration\Option;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ExplicitBoolCompareRector::class);
    $services->set(ReturnTypeDeclarationRector::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
};
