<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(
        AddThrowsTagRector::class,
        [
            'LogicException',
            'ValueError',
        ],
    );
};
