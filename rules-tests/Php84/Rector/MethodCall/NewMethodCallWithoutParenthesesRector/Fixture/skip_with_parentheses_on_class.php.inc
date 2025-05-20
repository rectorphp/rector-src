<?php

namespace Rector\Tests\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector\Fixture;

final class SkipWithParenthesesOnClass
{
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return (new (static::getModel()))->newQuery()
            ->orderByDesc('received_at');
    }
}