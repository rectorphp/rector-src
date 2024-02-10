<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\ReturnEmptyNodes\Source\ReturnEmptyStmtsRector;

return RectorConfig::configure()
    ->withRules([ReturnEmptyStmtsRector::class]);
