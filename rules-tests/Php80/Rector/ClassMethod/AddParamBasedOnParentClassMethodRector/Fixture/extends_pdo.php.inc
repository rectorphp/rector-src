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
    public function query(
        #[\JetBrains\PhpStorm\Internal\LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $query,
        #[\JetBrains\PhpStorm\Internal\LanguageLevelTypeAware(['8.0' => 'int|null'], default: '')] $fetchMode = null,
        #[\JetBrains\PhpStorm\Internal\LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] ...$fetch_mode_args
    ) {
        $args = func_get_args();
        return parent::query($args[0]);
    }
}

?>
