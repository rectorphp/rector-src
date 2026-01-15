<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class SkipInsideAttribute
{
    #[Idempotency(key: static function(Command $command) : IdempotencyKey {
        return new IdempotencyKey(
           'key',
        );
    }, seconds: 604_800)]
    public function __invoke(Command $command)
    {}
}
