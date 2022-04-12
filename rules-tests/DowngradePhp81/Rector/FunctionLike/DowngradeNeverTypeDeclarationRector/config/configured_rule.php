<?php

declare(strict_types=1);

use Rector\DowngradePhp81\Rector\FunctionLike\DowngradeNeverTypeDeclarationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeNeverTypeDeclarationRector::class);
};
