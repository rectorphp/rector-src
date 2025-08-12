<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\ValueObject\WrapFuncCallWithPhpVersionIdChecker;
use Rector\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallArgRector;
use Rector\Removing\ValueObject\RemoveFuncCallArg;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\FuncCall\WrapFuncCallWithPhpVersionIdCheckerRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([ArrayFirstLastRector::class]);

    $rectorConfig->ruleWithConfiguration(
        RemoveFuncCallArgRector::class,
        [
            new RemoveFuncCallArg('openssl_pkey_derive', 2),
            // https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_the_exclude_disabled_parameter_of_get_defined_functions
            new RemoveFuncCallArg('get_defined_functions', 0),
        ]
    );

    // https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_splobjectstoragecontains_splobjectstorageattach_and_splobjectstoragedetach
    $rectorConfig->ruleWithConfiguration(
        RenameMethodRector::class,
        [
            new MethodCallRename('SplObjectStorage', 'contains', 'offsetExists'),
            new MethodCallRename('SplObjectStorage', 'attach', 'offsetSet'),
            new MethodCallRename('SplObjectStorage', 'detach', 'offsetUnset'),
        ]
    );

    $rectorConfig->ruleWithConfiguration(
        RenameFunctionRector::class,
        [
            // https://wiki.php.net/rfc/deprecations_php_8_5#formally_deprecate_socket_set_timeout
            'socket_set_timeout' => 'stream_set_timeout',

            // https://wiki.php.net/rfc/deprecations_php_8_5#formally_deprecate_mysqli_execute
            'mysqli_execute' => 'mysqli_stmt_execute',
        ]
    );

    // https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_no-op_functions_from_the_resource_to_object_conversion
    $rectorConfig->ruleWithConfiguration(
        WrapFuncCallWithPhpVersionIdCheckerRector::class,
        [
            new WrapFuncCallWithPhpVersionIdChecker('curl_close', 80500),
            new WrapFuncCallWithPhpVersionIdChecker('curl_share_close', 80500),
            new WrapFuncCallWithPhpVersionIdChecker('finfo_close', 80500),
            new WrapFuncCallWithPhpVersionIdChecker('imagedestroy', 80500),
            new WrapFuncCallWithPhpVersionIdChecker('xml_parser_free', 80500),
        ]
    );
};
