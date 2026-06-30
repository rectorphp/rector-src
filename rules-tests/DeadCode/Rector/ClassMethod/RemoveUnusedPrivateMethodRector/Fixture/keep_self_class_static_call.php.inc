<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

// a private method called through a class-string static call, self::class::sampleClass(),
// is not detected by the usage analyzer - keep it
final class KeepSelfClassStaticCall
{
    public function run(): object
    {
        return self::class::sampleClass();
    }

    private function sampleClass(): object
    {
        return new \stdClass();
    }
}
