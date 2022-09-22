<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

function y()
{
    if (rand() < 0.5) {
        return null;
    }

    return json_decode('something', null, 512, JSON_THROW_ON_ERROR);
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

function y()
{
    if (rand() < 0.5) {
        return null;
    }

    return json_decode('something', null, 512, JSON_THROW_ON_ERROR);
}

?>
