<?php

declare(strict_types=1);

use Rector\MysqlToMysqli\Rector\FuncCall\MysqlFuncCallToMysqliRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(MysqlFuncCallToMysqliRector::class);
};
