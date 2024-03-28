<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\FileWithoutNamespace\RectorConfigBuilderRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

class SomeClass
{
    public const RULES = [
        RectorConfigBuilderRector::class,
        TypedPropertyFromAssignsRector::class,
    ];
}

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules(SomeClass::RULES);
};

?>
-----
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\FileWithoutNamespace\RectorConfigBuilderRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

class SomeClass
{
    public const RULES = [
        RectorConfigBuilderRector::class,
        TypedPropertyFromAssignsRector::class,
    ];
}

return \Rector\Config\RectorConfig::configure()
    ->withRules(SomeClass::RULES);

?>
