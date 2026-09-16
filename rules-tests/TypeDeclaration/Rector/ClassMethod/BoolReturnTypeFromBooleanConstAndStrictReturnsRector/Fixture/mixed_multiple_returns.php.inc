<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstAndStrictReturnsRector\Fixture;

final class MixedMultipleReturns
{
    private $context;

    public function checkContext($context)
    {
        if (empty($this->context)) {
            return true;
        }

        if ($this->context == $context) {
            return true;
        }

        return 0 === stripos($this->context, (string) $context);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstAndStrictReturnsRector\Fixture;

final class MixedMultipleReturns
{
    private $context;

    public function checkContext($context): bool
    {
        if (empty($this->context)) {
            return true;
        }

        if ($this->context == $context) {
            return true;
        }

        return 0 === stripos($this->context, (string) $context);
    }
}

?>
