<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstAndStrictReturnsRector\Fixture;

final class MixedConstAndStrict
{
    public function checkContext($context)
    {
        if ($context === []) {
            return true;
        }

        return count($context) > 0;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstAndStrictReturnsRector\Fixture;

final class MixedConstAndStrict
{
    public function checkContext($context): bool
    {
        if ($context === []) {
            return true;
        }

        return count($context) > 0;
    }
}

?>
