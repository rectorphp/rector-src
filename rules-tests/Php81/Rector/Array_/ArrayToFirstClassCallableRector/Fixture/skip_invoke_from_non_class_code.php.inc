<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeExternalObject;

Route::get('verify-email', [SomeExternalObject::class, '__invoke'])
    ->name('verification.notice');
