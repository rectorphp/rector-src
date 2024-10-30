<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Php83\Rector\FuncCall\CombineHostPortLdapUriRector;
use Rector\Php83\Rector\FuncCall\RemoveGetClassNoArgsRector;
use Rector\Php83\Rector\FuncCall\RemoveGetParentClassNoArgsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        AddOverrideAttributeToOverriddenMethodsRector::class,
        AddTypeToConstRector::class,
        CombineHostPortLdapUriRector::class,
        RemoveGetClassNoArgsRector::class,
        RemoveGetParentClassNoArgsRector::class,
    ]);
};
