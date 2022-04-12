<?php

declare(strict_types=1);

use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(NullToStrictStringFuncCallArgRector::class);
};
