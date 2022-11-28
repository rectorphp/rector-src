<?php

declare(strict_types=1);

namespace Rector\Core\NodeManipulator;

use PhpParser\Node\FunctionLike;
use Rector\NodeNameResolver\NodeNameResolver;

final class FunctionLikeManipulator
{
    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
    ) {
    }

    /**
     * @return string[]
     */
    public function resolveParamNames(FunctionLike $functionLike): array
    {
        $paramNames = [];

        foreach ($functionLike->getParams() as $param) {
            $paramNames[] = $this->nodeNameResolver->getName($param);
        }

        return $paramNames;
    }
}
