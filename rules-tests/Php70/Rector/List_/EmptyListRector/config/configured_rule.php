<?php

declare(strict_types=1);

use Rector\Php70\Rector\List_\EmptyListRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(EmptyListRector::class);
};
