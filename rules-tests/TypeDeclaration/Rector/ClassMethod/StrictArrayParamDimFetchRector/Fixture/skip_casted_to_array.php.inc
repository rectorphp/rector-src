<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipCastedToArray
{
    public function resolve($params = [])
    {
        $params = (array) $params;

        foreach ($params as $key => $param) {
            echo $params[$key];
        }
    }
}
