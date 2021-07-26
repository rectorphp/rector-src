<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\CompleteVarDocTypePropertyRector\Fixture;

use stdClass;

final class KeepSimpleClass
{
    /**
     * @var stdClass|null
     */
    public $obj;

    public function run()
    {
        $this->obj = new stdClass();
    }
}