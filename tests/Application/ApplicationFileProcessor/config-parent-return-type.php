<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->cacheDirectory(sys_get_temp_dir() . '/_rector_cache_file_dependency_test');
    $rectorConfig->rule(AddReturnTypeDeclarationBasedOnParentClassMethodRector::class);
};
