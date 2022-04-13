<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\TypedPropertyByVarAndAssign\Fixture;

class SkipUnionNotIncludedInAssignedTypes
{
    /**
     * @var string|false|null
     */
    private $property;

    public function run()
    {
        $this->property = '';
    }

    public function run2()
    {
        $this->property = true;
    }

    public function run3()
    {
        $this->property = null;
    }
}
