<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

use stdClass;

class HasNullDefaultValue
{
    /**
     * @var stdClass
     */
    private $stdClass = null;

    public function __construct(stdClass $stdClass)
    {
        $this->stdClass = $stdClass;
    }
}

?>
