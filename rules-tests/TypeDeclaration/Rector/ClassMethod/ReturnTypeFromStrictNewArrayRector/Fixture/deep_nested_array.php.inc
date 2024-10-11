<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class DeepNestedArray
{
    public function test() {
        $arr = [];
        $rows = $this->getRows();
        foreach ($rows as $row) {
            $arr[$row["a"]][$row["b"]] = ["c" => $row["c"]];
        }
        return $arr;
    }

    private function getRows(): array {
        return [
            ["a" => 1, "b" => 2, "c" => 3, "d" => 4],
        ];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class DeepNestedArray
{
    /**
     * @return array<int|string, non-empty-array<(int | string), array{c: mixed}>>
     */
    public function test(): array {
        $arr = [];
        $rows = $this->getRows();
        foreach ($rows as $row) {
            $arr[$row["a"]][$row["b"]] = ["c" => $row["c"]];
        }
        return $arr;
    }

    private function getRows(): array {
        return [
            ["a" => 1, "b" => 2, "c" => 3, "d" => 4],
        ];
    }
}

?>
