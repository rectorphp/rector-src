<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

class StringAppendVariable
{
    public function resolve(array $data)
    {
        $content = '';

        foreach ($data as $value) {
            $content .= $value;
        }

        return $content;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

class StringAppendVariable
{
    public function resolve(array $data): string
    {
        $content = '';

        foreach ($data as $value) {
            $content .= $value;
        }

        return $content;
    }
}

?>
