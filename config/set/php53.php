<?php

declare(strict_types=1);

use Rector\Php53\Rector\FuncCall\DirNameFileConstantToDirConstantRector;
use Rector\Php53\Rector\Ternary\TernaryToElvisRector;
use Rector\Php53\Rector\Variable\ReplaceHttpServerVarsByServerRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(TernaryToElvisRector::class);
    $services->set(DirNameFileConstantToDirConstantRector::class);
    $services->set(ReplaceHttpServerVarsByServerRector::class);
};
