<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObjectFactory;

use PhpParser\Node\Expr\Error;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use Rector\Naming\ValueObject\ParamRename;
use Rector\NodeNameResolver\NodeNameResolver;

final readonly class ParamRenameFactory
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function createFromResolvedExpectedName(
        FunctionLike $functionLike,
        Param $param,
        string $expectedName
    ): ?ParamRename {
        if ($param->var instanceof Error) {
            return null;
        }

        $currentName = $this->nodeNameResolver->getName($param->var);
        if ($currentName === null) {
            return null;
        }

        return new ParamRename($currentName, $expectedName, $param, $param->var, $functionLike);
    }
}
