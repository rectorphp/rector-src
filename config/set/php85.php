<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([ArrayFirstLastRector::class]);

    // https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_no-op_functions_from_the_resource_to_object_conversion
    $rectorConfig->ruleWithConfiguration(
        RemoveFuncCallRector::class,
        [
            'finfo_close',
            'xml_parser_free',
            'curl_close',
            'curl_share_close',
            'imagedestroy',
        ]
    );
};
