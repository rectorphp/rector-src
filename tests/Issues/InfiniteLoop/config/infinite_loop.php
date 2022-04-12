<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector;
use Rector\Core\Tests\Issues\InfiniteLoop\Rector\MethodCall\InfinityLoopRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(InfinityLoopRector::class);
    $services->set(SimplifyDeMorganBinaryRector::class);
};
