<?php

declare(strict_types=1);

use Rector\Transform\Rector\FuncCall\FuncCallToNewRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(FuncCallToNewRector::class)
        ->configure([
            'collection' => 'Collection',
        ]);
};
