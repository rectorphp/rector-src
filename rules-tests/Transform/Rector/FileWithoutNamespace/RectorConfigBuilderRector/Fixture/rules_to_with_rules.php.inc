<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\FileWithoutNamespace\RectorConfigBuilderRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReturnUnionTypeRector::class);
    $rectorConfig->rules([
        RectorConfigBuilderRector::class,
        TypedPropertyFromAssignsRector::class
    ]);
};

?>
-----
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\FileWithoutNamespace\RectorConfigBuilderRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

return \Rector\Config\RectorConfig::configure()->withRules([ReturnUnionTypeRector::class, RectorConfigBuilderRector::class, TypedPropertyFromAssignsRector::class]);

?>
