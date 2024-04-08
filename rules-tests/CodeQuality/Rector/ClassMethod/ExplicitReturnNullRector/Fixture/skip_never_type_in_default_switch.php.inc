<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

final class SkipNeverTypeInDefaultSwitch
{
    public function run1(string $param) {
        switch ($param) {
            case 'foo':
                return 'bar';
            case 'marco':
                return 'polo';
            default:
                $this->error();
        }
    }

    private function error(): never {
        throw new \RuntimeException('Nope');
    }
}
