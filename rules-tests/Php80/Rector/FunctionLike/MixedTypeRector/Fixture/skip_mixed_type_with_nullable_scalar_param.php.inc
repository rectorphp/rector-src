<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

final class SkipMixedTypeWithNullableScalarParam
{
    /**
     * @param ?scalar $value
     */
    public function __construct(
        public readonly mixed $value,
    ) {
    }
}