<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\UnionTypesRector\Fixture;

class SkipUnionTraitString
{
    /**
     * @param string|trait-string $fromId
     */
    public function withFromId(string $fromId): static
    {
        return $this;
    }
}
