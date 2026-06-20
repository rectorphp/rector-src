<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class ScopedDynamicVariable
{
    public function build(array $params): string
    {
        $addHelpMessage = true;

        foreach (['addHelpMessage'] as $f) {
            if (isset($params[$f])) {
                ${$f} = (bool) $params[$f];
            }
        }

        if ($addHelpMessage) {
            return 'help';
        }

        return 'no help';
    }

    public function differentScope()
    {
        $int = 1;
        if ($int === 1) {
            return 'hi';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class ScopedDynamicVariable
{
    public function build(array $params): string
    {
        $addHelpMessage = true;

        foreach (['addHelpMessage'] as $f) {
            if (isset($params[$f])) {
                ${$f} = (bool) $params[$f];
            }
        }

        if ($addHelpMessage) {
            return 'help';
        }

        return 'no help';
    }

    public function differentScope()
    {
        $int = 1;
        return 'hi';
    }
}

?>