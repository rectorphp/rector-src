<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->bootstrapFiles([
        __DIR__  . '/constants.php',
    ]);
};

?>
-----
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return \Rector\Config\RectorConfig::configure()->withBootstrapFiles([
    __DIR__  . '/constants.php',
]);

?>
