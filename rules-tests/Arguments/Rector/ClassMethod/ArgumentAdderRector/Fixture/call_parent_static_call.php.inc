<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeParentWithProtectedMethod;
use stdClass;

class CallParentStaticCall extends SomeParentWithProtectedMethod
{
    protected function run(mixed $value, ?stdClass $element): string
    {
        $result = parent::run($value, $element);
        return str_replace('a', 'b', $result);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeParentWithProtectedMethod;
use stdClass;

class CallParentStaticCall extends SomeParentWithProtectedMethod
{
    protected function run(mixed $value, ?stdClass $element, bool $inline = false): string
    {
        $result = parent::run($value, $element, $inline);
        return str_replace('a', 'b', $result);
    }
}

?>
