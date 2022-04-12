<?php

declare(strict_types=1);

use Rector\Php73\Rector\FuncCall\SetCookieRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SetCookieRector::class);
};
