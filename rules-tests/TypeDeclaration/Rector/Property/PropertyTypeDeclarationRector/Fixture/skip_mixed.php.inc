<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\PropertyTypeDeclarationRector\Fixture;

class ParameterBag
{
    public $params;

    public function __construct()
    {
        $this->params = func_get_args();
    }

    public function addParam($param)
    {
        $this->params[] = $param;
    }

    public function unwrap()
    {
        return $this->params;
    }
}
?>
