<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnEmptyString
{
    private function generateStyle(array $values, string $padding)
    {
        $style = '';

        if ('' != $values['background-color']) {
            $style = 'something';
        }

        if ('' != $style) {
            return ' style="'.$style.'"';
        }

        return $style;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnEmptyString
{
    private function generateStyle(array $values, string $padding): string
    {
        $style = '';

        if ('' != $values['background-color']) {
            $style = 'something';
        }

        if ('' != $style) {
            return ' style="'.$style.'"';
        }

        return $style;
    }
}

?>
