<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

use Psr\Container\ContainerInterface;
use stdClass;

class SkipMixedReturnFromInterface implements ContainerInterface
{
    /**
     * @return stdClass|[]
     */
    public function get($id) {
        if (rand(0,1)) {
            return new stdClass();
        }

        return [];
    }

    public function has($id): bool { return true; }
}
