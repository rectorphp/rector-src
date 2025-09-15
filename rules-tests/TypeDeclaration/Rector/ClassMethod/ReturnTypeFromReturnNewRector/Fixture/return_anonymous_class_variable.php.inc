<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class ReturnAnonymousClassVariable
{
    public function action()
    {
        $data = new class
        {
            // any properties
        };

        // some bussines logic to fill the data

        return $data;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

final class ReturnAnonymousClassVariable
{
    public function action(): object
    {
        $data = new class
        {
            // any properties
        };

        // some bussines logic to fill the data

        return $data;
    }
}

?>
