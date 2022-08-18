<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(
        \Rector\Php80\Rector\Property\NestedAnnotationToAttributeRector::class,
        [
            'old_value' => 'newValue',
        ]
    );
};
