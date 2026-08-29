<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class LeadingBackslash
{
    public function run(array $input)
    {
        return \array_map('\trim', $input);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class LeadingBackslash
{
    public function run(array $input)
    {
        return \array_map(\trim(...), $input);
    }
}

?>
