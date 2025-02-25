<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

class UnionClosure
{
    private $property;

    public function run(): void
    {
        if (rand(0, 1)) {
            $this->property = function (): void {
            };
        } else {
            $this->property = false;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

class UnionClosure
{
    private \Closure|bool|null $property = null;

    public function run(): void
    {
        if (rand(0, 1)) {
            $this->property = function (): void {
            };
        } else {
            $this->property = false;
        }
    }
}

?>
