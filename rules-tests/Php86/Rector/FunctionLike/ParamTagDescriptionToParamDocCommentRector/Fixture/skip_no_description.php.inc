<?php

namespace Rector\Tests\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector\Fixture;

final class SkipNoDescription
{
    /**
     * @param array<string, int> $scores
     */
    public function run(array $scores): void
    {
    }
}
