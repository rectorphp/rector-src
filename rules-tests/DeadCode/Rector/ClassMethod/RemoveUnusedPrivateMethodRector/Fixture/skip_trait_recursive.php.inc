<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Source\RecursiveTrait;

/**
 * @method static Builder<static>|BaseModel query()
 */
class BaseModel extends Model {
    use RecursiveTrait;

    public function parent(): BelongsTo {
        return $this->belongsTo(self::class);
    }
}