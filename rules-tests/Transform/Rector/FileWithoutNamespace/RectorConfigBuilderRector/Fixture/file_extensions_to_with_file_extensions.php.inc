<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->fileExtensions(['php', 'phtml']);
};

?>
-----
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return \Rector\Config\RectorConfig::configure()
    ->withFileExtensions(['php', 'phtml']);

?>
