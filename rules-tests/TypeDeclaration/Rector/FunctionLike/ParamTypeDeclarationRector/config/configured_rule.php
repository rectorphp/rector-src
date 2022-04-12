<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\FunctionLike\ParamTypeDeclarationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ParamTypeDeclarationRector::class);
};
