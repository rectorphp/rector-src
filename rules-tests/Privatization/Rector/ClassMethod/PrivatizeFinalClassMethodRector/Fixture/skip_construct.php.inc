<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

/**
 * both classes need to be in same fixture on purpose to reproduce the issue
 */
readonly abstract class A {
    public static function a():A {
        return new B();
    }
}

final readonly class B extends A {
    protected function __construct() {

    }
}
