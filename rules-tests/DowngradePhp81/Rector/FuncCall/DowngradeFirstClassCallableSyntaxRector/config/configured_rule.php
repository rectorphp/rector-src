<?php

declare(strict_types=1);

use Rector\DowngradePhp81\Rector\FuncCall\DowngradeFirstClassCallableSyntaxRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeFirstClassCallableSyntaxRector::class);
};
