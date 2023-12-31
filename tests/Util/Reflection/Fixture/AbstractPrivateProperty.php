<?php
declare(strict_types=1);

namespace Rector\Tests\Util\Reflection\Fixture;

abstract class AbstractPrivateProperty
{
    /**
     * @var int
     */
    private $parentValue = 10;

    public function getParentValue(): int
    {
        return $this->parentValue;
    }
}
