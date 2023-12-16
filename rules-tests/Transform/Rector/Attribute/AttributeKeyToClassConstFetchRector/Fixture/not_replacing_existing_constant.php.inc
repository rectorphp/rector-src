<?php

namespace Rector\Tests\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector\Fixture;

use Doctrine\ORM\Mapping\Column;
use Rector\Tests\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector\Source\Constant;
use Rector\Tests\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector\Source\TestAttribute;

class SomeClass
{
    #[TestAttribute(type: Constant::VALUE)]
    public string $name;
}

?>
