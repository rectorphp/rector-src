<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class SkipDefaultTypeDifferent
{
    /**
     * @var bool|int
     */
    protected $property = true;

    public function run()
    {
        $this->property = 100;
    }
}

?>