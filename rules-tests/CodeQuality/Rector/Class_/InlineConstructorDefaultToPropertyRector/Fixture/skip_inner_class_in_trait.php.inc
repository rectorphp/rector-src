<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

trait SkipInnerClassInTrait {
    public function blah()
    {
        return (new class() {
            public function foo()
            {
                return (new class() {
                    use SkipInnerClassInTrait;
                });
            }
        });
    }
}