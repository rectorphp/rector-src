<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([__DIR__ . '/../php71.php', __DIR__ . '/up-to-php70.php']);
};
