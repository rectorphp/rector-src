<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Source\SomeExternalObject;

Route::get('verify-email', [SomeExternalObject::class, '__invoke'])
    ->name('verification.notice');
