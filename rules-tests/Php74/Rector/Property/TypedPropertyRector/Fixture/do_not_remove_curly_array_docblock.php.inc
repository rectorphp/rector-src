<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DoNotRemoveCurlyArrayDocblock
{
    /**
     * @var array{a: \stdClass[], b: \DateTime[]}
     */
    private $property = [
        'a' => [],
        'b' => [],
    ];

    public function getProperty()
    {
        if ($this->property['a'] === [] && $this->property['b'] === []) {
            $this->property['a'] = [new stdClass()];
            $this->property['b'] = [new \DateTime()];
        }

        return $this->property;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DoNotRemoveCurlyArrayDocblock
{
    /**
     * @var array{a: \stdClass[], b: \DateTime[]}
     */
    private array $property = [
        'a' => [],
        'b' => [],
    ];

    public function getProperty()
    {
        if ($this->property['a'] === [] && $this->property['b'] === []) {
            $this->property['a'] = [new stdClass()];
            $this->property['b'] = [new \DateTime()];
        }

        return $this->property;
    }
}

?>