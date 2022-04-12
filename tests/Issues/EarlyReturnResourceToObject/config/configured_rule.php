<?php

declare(strict_types=1);

use Rector\Php80\Rector\FuncCall\Php8ResourceReturnToObjectRector;
use Rector\Set\ValueObject\SetList;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $containerConfigurator->import(SetList::EARLY_RETURN);

    $services = $containerConfigurator->services();
    $services->set(Php8ResourceReturnToObjectRector::class);
};
