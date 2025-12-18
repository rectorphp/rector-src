<?php

namespace Rector\Tests\Issues\NullCompareInTrait\Fixture;

trait SkipBoolTypeTrait
{
    /** @var array<string,class-string> */
    protected array $dispatchesEvents = [];

    protected function fireResourceEvent(string $event, SomeModel $model, mixed ...$args): void
    {
        $class = $this->dispatchesEvents[$event] ?? null;

        if ($class === null) {
            return;
        }

        some_event(new $class($model, ...$args));
    }
}

?>
