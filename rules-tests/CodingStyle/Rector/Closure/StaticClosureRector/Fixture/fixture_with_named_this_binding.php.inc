<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Fixture;

use Closure;
use Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Source\BindObject;

class FixtureWithNamedThisBinding
{
    public function first()
    {
        return BindObject::call(closure: function () {
            return 'bar';
        }, closureTwo: function () {
            return 'foo';
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Fixture;

use Closure;
use Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Source\BindObject;

class FixtureWithNamedThisBinding
{
    public function first()
    {
        return BindObject::call(closure: function () {
            return 'bar';
        }, closureTwo: static function () {
            return 'foo';
        });
    }
}

?>
