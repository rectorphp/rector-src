<?php

namespace Rector\Tests\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector\Fixture;

/**
 * @param string $glue Glue between parts
 * @param string ...$parts Parts to join
 */
function joinParts(string &$glue, string ...$parts): string
{
    return implode($glue, $parts);
}

