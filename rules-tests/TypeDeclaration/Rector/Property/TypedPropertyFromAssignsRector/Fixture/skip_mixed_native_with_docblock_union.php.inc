<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use DateTime;
use stdClass;

final class SkipNativeWithDocblockUnion
{
    private $property;

    public function run1()
    {
        $this->property = new stdClass;
    }

    /**
     * @param stdClass|DateTime $property
     */
    public function run2($property)
    {
        $this->property = $property;
    }
}
