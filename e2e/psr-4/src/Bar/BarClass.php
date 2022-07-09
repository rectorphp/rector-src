<?php

namespace Test;

class BarClass {
    public function test(): FooClass {
        return new FooClass;
    }
}
