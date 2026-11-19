<?php

namespace Rector\Tests\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector\Fixture;

final class MultilineDescription
{
    /**
     * @param string $query Terms to search,
     *                      separated by spaces
     */
    public function run(
        string $query,
    ): void {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector\Fixture;

final class MultilineDescription
{
    public function run(
        /**
         * Terms to search,
         * separated by spaces
         */
        string $query,
    ): void {
    }
}

?>
