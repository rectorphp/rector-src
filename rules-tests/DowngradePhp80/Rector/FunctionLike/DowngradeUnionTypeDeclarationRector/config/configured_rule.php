<?php

declare(strict_types=1);

use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeUnionTypeDeclarationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeUnionTypeDeclarationRector::class);
};
