<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->skip([
        __DIR__ . '/src/migrations',
    ]);
    $rectorConfig->rule(ReturnUnionTypeRector::class);
};

?>
-----
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector;

return \Rector\Config\RectorConfig::configure()
    ->withSkip([__DIR__ . '/src/migrations'])
    ->withRules([ReturnUnionTypeRector::class]);

?>
