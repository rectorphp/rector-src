<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
		SetList::CODE_QUALITY,
		SetList::CODING_STYLE,
		SetList::DEAD_CODE,
		SetList::INSTANCEOF,
		SetList::PRIVATIZATION,
		SetList::STRICT_BOOLEANS,
		SetList::TYPE_DECLARATION,
    ]);
};
