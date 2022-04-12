<?php

declare(strict_types=1);

use Rector\DowngradePhp70\Rector\FunctionLike\DowngradeThrowableTypeDeclarationRector;
use Rector\DowngradePhp74\Rector\ArrowFunction\ArrowFunctionToAnonymousFunctionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ArrowFunctionToAnonymousFunctionRector::class);
    $services->set(DowngradeThrowableTypeDeclarationRector::class);
};
