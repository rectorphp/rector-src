<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddParamTypeSplFixedArrayRector::class);
};
