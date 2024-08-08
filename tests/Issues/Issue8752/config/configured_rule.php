<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use \Rector\Tests\Issues\Issue8752\Source\RemoveDoctrineAnnotationValueRector;

return RectorConfig::configure()
    ->withRules([RemoveDoctrineAnnotationValueRector::class]);

