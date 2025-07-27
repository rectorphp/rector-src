<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponse;

final class SameVariableReturnNested
{
    public function action()
    {
        $response = new SomeResponse();

        if (mt_rand(0, 1)) {
            $response->setBody('... some body content');

            return $response;
        }

        return $response;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponse;

final class SameVariableReturnNested
{
    public function action(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source\SomeResponse
    {
        $response = new SomeResponse();

        if (mt_rand(0, 1)) {
            $response->setBody('... some body content');

            return $response;
        }

        return $response;
    }
}

?>
