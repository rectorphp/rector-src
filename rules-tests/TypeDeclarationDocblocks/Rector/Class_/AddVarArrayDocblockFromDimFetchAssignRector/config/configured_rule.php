<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\Class_\AddVarArrayDocblockFromDimFetchAssignRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddVarArrayDocblockFromDimFetchAssignRector::class);
};
