<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class NamespacedFunction
{
    public function run(): array
    {
        // String callable: resolved from the global namespace at runtime -> works.
        return array_map('Cake\Utility\Inflector::underscore', ['FooBar', 'BazQux']);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class NamespacedFunction
{
    public function run(): array
    {
        // String callable: resolved from the global namespace at runtime -> works.
        return array_map(\Cake\Utility\Inflector::underscore(...), ['FooBar', 'BazQux']);
    }
}

?>
