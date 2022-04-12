<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SKIP, [__DIR__ . '/../SourceWithBrokenSymlinks/folder1']);
};
