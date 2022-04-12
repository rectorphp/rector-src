<?php

declare(strict_types=1);

use Rector\Arguments\Rector\FuncCall\SwapFuncCallArgumentsRector;
use Rector\Arguments\ValueObject\SwapFuncCallArguments;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SwapFuncCallArgumentsRector::class)
        ->configure([new SwapFuncCallArguments('some_function', [1, 0])]);
};
