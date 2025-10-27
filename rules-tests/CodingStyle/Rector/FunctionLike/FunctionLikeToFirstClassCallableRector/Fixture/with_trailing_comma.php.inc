<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

final class WithTrailingComma
{
    public function run(): void
    {
        $array = [1, 2, 3];

        array_map(
            fn($item) => var_dump(
                $item,
            ),
            $array
        );
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

final class WithTrailingComma
{
    public function run(): void
    {
        $array = [1, 2, 3];

        array_map(
            var_dump(
                ...
            ),
            $array
        );
    }
}

?>
