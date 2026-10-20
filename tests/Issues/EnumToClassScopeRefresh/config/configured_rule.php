<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp80\Rector\Enum_\DowngradeEnumToConstantListClassRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return RectorConfig::configure()
    ->withRules([DowngradeEnumToConstantListClassRector::class])
    ->withConfiguredRule(RenameMethodRector::class, [
        new MethodCallRename('SomeClass', 'oldMethod', 'newMethod'),
    ]);
