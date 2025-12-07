<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Source\BindObject;
use function Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Source\bind_on_object;

BindObject::call(closure: function () {
    echo 'static call';
});

(new BindObject())->callOnObject(function () {
    echo 'method call';
});

bind_on_object(function () {
    echo 'closure func call 0';
}, null, function () {
    echo 'closure func call 0';
});

?>
