<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class IfElseReturn
{
    public function run($meetups)
    {
        return array_filter($meetups, function (?Meetup $meetup) {
            if (is_object($meetup)) {
                return true;
            }

            return false;
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\Fixture;

class IfElseReturn
{
    public function run($meetups)
    {
        return array_filter($meetups, fn(?Meetup $meetup) => is_object($meetup) ? true : false);
    }
}

?>
