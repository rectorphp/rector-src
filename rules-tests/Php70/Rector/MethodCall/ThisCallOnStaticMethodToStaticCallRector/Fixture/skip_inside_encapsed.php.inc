<?php

namespace Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\Fixture;

class SkipInsideEncapsed {
    public function foo() {
        echo "{$this->getLogDate()}";
    }

    private static function getLogDate(): string {
        return 'foo';
    }
}
