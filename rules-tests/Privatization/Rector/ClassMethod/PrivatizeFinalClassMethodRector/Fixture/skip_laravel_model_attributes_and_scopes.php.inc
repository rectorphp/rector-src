<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

final class User extends Model
{
    protected function getSomeAttribute(): mixed
    {
    }

    protected function setSomeAttribute(): mixed
    {
    }

    protected function foo(): Attribute
    {
    }

    protected function scopeFoo(): mixed
    {
    }

    #[Scope]
    protected function bar(): mixed
    {
    }
}
