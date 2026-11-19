<?php

namespace Rector\Tests\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector\Fixture;

final class KeepTypeRicherThanNative
{
    /**
     * @param array<string, int> $scores Scores by player name
     * @param string $name
     */
    public function run(
        array $scores,
        string $name,
    ): void {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector\Fixture;

final class KeepTypeRicherThanNative
{
    /**
     * @param array<string, int> $scores
     * @param string $name
     */
    public function run(
        /** Scores by player name */
        array $scores,
        string $name,
    ): void {
    }
}

?>
