<?php

declare(strict_types=1);

use Rector\DowngradePhp73\Rector\FuncCall\SetCookieOptionsArrayToArgumentsRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SetCookieOptionsArrayToArgumentsRector::class);
};
