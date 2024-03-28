<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\FileWithoutNamespace\RectorConfigBuilderRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RectorConfigBuilderRector::class);
};

?>
-----
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\FileWithoutNamespace\RectorConfigBuilderRector;

return \Rector\Config\RectorConfig::configure()
    ->withRules([RectorConfigBuilderRector::class]);

?>
