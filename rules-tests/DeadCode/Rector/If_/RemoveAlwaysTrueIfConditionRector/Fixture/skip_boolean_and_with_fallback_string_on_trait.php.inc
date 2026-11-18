<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

trait SkipBooleanAndWithFallbackStringOnTrait
{
    public function check($id): string
    {
        $usage = $this->options['usage'] ?? 'create';

        if ($usage === 'update' && ! $id) {
            return 'missing';
        }

        if ($usage === 'create' && $id) {
            return 'exists';
        }

        return 'ok';
    }
}
