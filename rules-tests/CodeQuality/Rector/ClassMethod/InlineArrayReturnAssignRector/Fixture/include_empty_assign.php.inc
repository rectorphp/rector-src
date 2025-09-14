<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class IncludeEmptyAssign
{
    public function run($person2)
    {
        $person = [];

        $person[] = 'Timmy';
        $person['surname'] = 'Back';

        return $person;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class IncludeEmptyAssign
{
    public function run($person2)
    {
        return ['Timmy', 'surname' => 'Back'];
    }
}

?>
