<?php

declare(strict_types=1);

use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReturnTypeDeclarationRector::class);
    $services->set(ReadOnlyPropertyRector::class);
};
