<?php declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NameTypeResolver\Source;

use Rector\Tests\NodeTypeResolver\Source\AnotherClass;

class ParentCall extends AnotherClass
{
    public function getParameters()
    {
        parent::getParameters();
    }
}
