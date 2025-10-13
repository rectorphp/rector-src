<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class ConstantVariable
{
    public function test(string $input, $name)
    {
        $message = 'My name is %s';
        return sprintf($message, $name);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamStringTypeFromSprintfUseRector\Fixture;

final class ConstantVariable
{
    public function test(string $input, string $name)
    {
        $message = 'My name is %s';
        return sprintf($message, $name);
    }
}

?>
