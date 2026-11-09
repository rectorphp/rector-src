<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\InheritedConstructorFromOtherFile\Source\AddConstructorDependencyRector;

return RectorConfig::configure()
    ->withRules([AddConstructorDependencyRector::class]);
