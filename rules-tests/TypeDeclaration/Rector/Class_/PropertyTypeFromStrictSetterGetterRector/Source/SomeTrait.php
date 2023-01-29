<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Source;

use stdClass;

trait SomeTrait
{
    public function run()
    {
        $this->name = new stdClass;
    }
}