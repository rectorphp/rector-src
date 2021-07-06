<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;

class UnionToParent
{
    /**
     * @return MethodCall|PropertyFetch
     */
    public function get(MethodCall $methodCall, PropertyFetch $propertyFetch)
    {
        if (rand(0,1)) {
            return $methodCall;
        }

        return $propertyFetch;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;

class UnionToParent
{
    /**
     * @return MethodCall|PropertyFetch
     */
    public function get(MethodCall $methodCall, PropertyFetch $propertyFetch): \PhpParser\Node\Expr
    {
        if (rand(0,1)) {
            return $methodCall;
        }

        return $propertyFetch;
    }
}

?>
