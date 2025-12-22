<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([\Rector\Tests\Issues\FileWithoutNamespaceCompat\Rector\SubscribedToFileWithoutNamespaceRector::class]);
