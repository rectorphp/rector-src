<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

final class WithTrailingComma
{
    public function run(): void
    {
        $array = [1, 2, 3];

        $this->map(
            $array,
            fn($item) => var_dump(
                $item,
            )
        );
    }

    private function map(array $items, callable $callback): void {
		foreach($items as $i) {
        	$callback($i);
        }
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

        $this->map(
            $array,
            var_dump(
                ...
            )
        );
    }

    private function map(array $items, callable $callback): void {
		foreach($items as $i) {
        	$callback($i);
        }
    }
}

?>
