<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

/**
 * @experimental * 2025-09, experimental hidden set for type declaration in docblocks
 */
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules(\Rector\Config\Level\TypeDeclarationDocblocksLevel::RULES);
};
