<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

use stdClass;

class SkipCastSetGetMagic
{
    public function __set($key, $value)
    {
        $this->{$key} = (array) $value;
    }

    public function run(stdClass $value)
    {
        $this->property = $value;

        var_dump($value == (object) $this->property);
    }
}

(new SkipCastSetGetMagic())->run(new stdClass());

?>
