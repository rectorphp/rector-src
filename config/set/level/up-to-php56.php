<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([__DIR__ . '/../php56.php', __DIR__ . '/up-to-php55.php']);
};
