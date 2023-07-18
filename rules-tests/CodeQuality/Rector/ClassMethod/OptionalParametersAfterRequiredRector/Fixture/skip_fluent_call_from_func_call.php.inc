<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

class SkipFluentCallFromFuncCall
{
    public function getMissingDatabaseColumns(): array
    {
        return collect($missingCleanedColumns)
            ->map(function ($column, $key) {
                switch ($key) {
                    case 'id':
                        return "{$key}:id";
                    default:
                        return match ($column['type']) {
                            'array', 'object' => "{$key}:json:nullable",
                        };
                }
            })
            ->filter()
            ->all();
    }
}
