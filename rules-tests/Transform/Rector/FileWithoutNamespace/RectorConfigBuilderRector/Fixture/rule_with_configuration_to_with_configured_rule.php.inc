<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameFunctionRector::class, [
            'is_real' => 'is_float',
        ]);
};

?>
-----
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return \Rector\Config\RectorConfig::configure()
    ->withConfiguredRule(RenameFunctionRector::class, ['is_real' => 'is_float']);

?>
