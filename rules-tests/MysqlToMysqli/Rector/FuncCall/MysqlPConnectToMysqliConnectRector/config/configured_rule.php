<?php

declare(strict_types=1);

use Rector\MysqlToMysqli\Rector\FuncCall\MysqlPConnectToMysqliConnectRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(MysqlPConnectToMysqliConnectRector::class);
};
