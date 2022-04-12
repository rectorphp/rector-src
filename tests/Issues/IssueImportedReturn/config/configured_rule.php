<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Privatization\Rector\Class_\RepeatedLiteralToClassConstantRector;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RepeatedLiteralToClassConstantRector::class);
    $services->set(ReturnTypeDeclarationRector::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
};
