<?php

namespace Rector\Tests\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector\Fixture;

final class Fixture
{
    /**
     * Returns up to $limit items matching the $query.
     *
     * @param string $query Terms to search
     * @param int $limit Maximum number of results
     */
    public function search(
        string $query,
        int $limit = 10,
    ): array {
        $items = [];

        return array_slice($items, 0, $limit);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php86\Rector\FunctionLike\ParamTagDescriptionToParamDocCommentRector\Fixture;

final class Fixture
{
    /**
     * Returns up to $limit items matching the $query.
     */
    public function search(
        /** Terms to search */
        string $query,
        /** Maximum number of results */
        int $limit = 10,
    ): array {
        $items = [];

        return array_slice($items, 0, $limit);
    }
}

?>
