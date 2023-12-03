<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

class SkipExtendsPDOQuery extends \PDO {
    public function query() {
        $args = func_get_args();
        return parent::query($args[0]);
    }
}
