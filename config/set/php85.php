<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([ArrayFirstLastRector::class]);

    // https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_splobjectstoragecontains_splobjectstorageattach_and_splobjectstoragedetach
    $rectorConfig->ruleWithConfiguration(
        RenameMethodRector::class,
        [
            new MethodCallRename('SplObjectStorage', 'contains', 'offsetExists'),
            new MethodCallRename('SplObjectStorage', 'attach', 'offsetSet'),
            new MethodCallRename('SplObjectStorage', 'detach', 'offsetUnset'),
        ]
    );
};
