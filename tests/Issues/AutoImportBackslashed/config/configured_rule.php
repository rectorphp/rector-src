<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;

use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(ExplicitBoolCompareRector::class);
    $services->set(ReturnTypeDeclarationRector::class);

    $parameters = $rectorConfig->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
};
