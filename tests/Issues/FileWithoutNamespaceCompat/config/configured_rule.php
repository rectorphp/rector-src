<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\FileWithoutNamespaceCompat\Rector\SubscribedToFileWithoutNamespaceRector;

return RectorConfig::configure()
    ->withRules([SubscribedToFileWithoutNamespaceRector::class]);
