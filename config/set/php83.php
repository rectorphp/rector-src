<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Php83\Rector\FuncCall\CombineHostPortLdapUriRector;
use Rector\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector;
use Rector\Php83\Rector\New_\ReadOnlyAnonymousClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        AddOverrideAttributeToOverriddenMethodsRector::class,
        AddTypeToConstRector::class,
        CombineHostPortLdapUriRector::class,
        RemoveGetClassGetParentClassNoArgsRector::class,
        ReadOnlyAnonymousClassRector::class,
    ]);
};
