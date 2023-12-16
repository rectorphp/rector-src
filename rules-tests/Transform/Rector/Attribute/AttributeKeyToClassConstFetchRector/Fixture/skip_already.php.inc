<?php

namespace Rector\Tests\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector\Fixture;

use Doctrine\ORM\Mapping\Column;

use Doctrine\DBAL\Types\Types;

class SkipAlready
{
    #[Column(type: Types::STRING)]
    public $name;
}

?>
