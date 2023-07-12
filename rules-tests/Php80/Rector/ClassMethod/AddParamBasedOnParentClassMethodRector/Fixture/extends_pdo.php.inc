<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

class PDOConnection extends \PDO {
    public function query() {
        $args = func_get_args();
        return parent::query($args[0]);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

class PDOConnection extends \PDO {
    public function query($query, $fetchMode = \PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = \null, $ctorargs = []) {
        $args = func_get_args();
        return parent::query($args[0]);
    }
}

?>
