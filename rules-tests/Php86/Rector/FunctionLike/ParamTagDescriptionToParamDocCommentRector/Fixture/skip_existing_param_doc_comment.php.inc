<?php

namespace Rector\Tests\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector\Fixture;

final class SkipExistingParamDocComment
{
    /**
     * @param string $query Terms to search
     */
    public function run(
        /** Search terms */
        string $query,
    ): void {
    }
}
