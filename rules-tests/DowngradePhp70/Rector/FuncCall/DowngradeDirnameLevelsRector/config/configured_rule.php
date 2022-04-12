<?php

declare(strict_types=1);

use Rector\DowngradePhp70\Rector\FuncCall\DowngradeDirnameLevelsRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeDirnameLevelsRector::class);
};
