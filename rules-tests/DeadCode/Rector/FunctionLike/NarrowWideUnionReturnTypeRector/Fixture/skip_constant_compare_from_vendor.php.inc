<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

use Monolog\Logger;
use Monolog\LogRecord;

final class SkipConstantCompareFromVendor
{
    public function createLogRecord(int $level): array|LogRecord
    {
        return Logger::API === 2
            ? ['level' => $level]
            : new LogRecord();
    }
}
