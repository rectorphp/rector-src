<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

trait SkipMixedFallbackNullOnTrait
{
    /**
     * @array<string, string>
     */
    private $dispatchesEvents;

    protected function fireResourceEvent(string $event, Model $model, mixed ...$args): void
    {
        if ($this->silent) {
            return;
        }

        $event = $this->dispatchesEvents[$event] ?? null;

        if (is_null($event)) {
            return;
        }

        event(new $event($model, ...$args));
    }
}
