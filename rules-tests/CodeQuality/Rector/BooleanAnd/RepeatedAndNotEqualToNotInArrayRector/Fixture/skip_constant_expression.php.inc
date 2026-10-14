<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\RepeatedAndNotEqualToNotInArrayRector\Fixture;

const ENVIRONMENT = 'local';

final class SkipConstantExpression
{
    public static bool $isEnv = (ENVIRONMENT !== 'local' && ENVIRONMENT !== 'dev' && ENVIRONMENT !== 'stage');

    public const IS_ENV = (ENVIRONMENT !== 'local' && ENVIRONMENT !== 'dev' && ENVIRONMENT !== 'stage');

    public function demo(bool $value = (ENVIRONMENT !== 'local' && ENVIRONMENT !== 'dev' && ENVIRONMENT !== 'stage')): void
    {
    }
}
