<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->autoloadPaths([
        __DIR__  . '/../autoload.php',
    ]);
};

?>
-----
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return \Rector\Config\RectorConfig::configure()->withAutoloadPaths([
    __DIR__  . '/../autoload.php',
]);

?>
