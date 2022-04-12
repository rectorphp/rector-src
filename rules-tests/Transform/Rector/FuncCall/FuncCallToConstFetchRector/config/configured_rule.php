<?php

declare(strict_types=1);

use Rector\Transform\Rector\FuncCall\FuncCallToConstFetchRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(FuncCallToConstFetchRector::class)
        ->configure([
            'php_sapi_name' => 'PHP_SAPI',
            'pi' => 'M_PI',
        ]);
};
