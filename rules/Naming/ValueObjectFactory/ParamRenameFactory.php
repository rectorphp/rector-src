<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObjectFactory;

use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Error;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\Naming\ValueObject\ParamRename;
use Rector\NodeNameResolver\NodeNameResolver;

final class ParamRenameFactory
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    public function createFromResolvedExpectedName(
        ClassMethod|Function_|ArrowFunction|Closure $functionLike,
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
