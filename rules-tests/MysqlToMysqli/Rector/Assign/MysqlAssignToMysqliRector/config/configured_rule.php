<?php

declare(strict_types=1);

use Rector\MysqlToMysqli\Rector\Assign\MysqlAssignToMysqliRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(MysqlAssignToMysqliRector::class);
};
