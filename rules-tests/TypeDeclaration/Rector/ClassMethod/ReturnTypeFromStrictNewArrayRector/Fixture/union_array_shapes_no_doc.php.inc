<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class UnionArrayShapesNoDoc
{
    public function getRowFormatBookEntry($abe, $onlyCashRegister = false)
    {
        $data = [];
        foreach ($abe as $x) {
            if ($onlyCashRegister) {
                $data[] = ['amount' => 1, 'cash_register_id' => 2, 'text' => 't'];
            } else {
                $data[] = ['amount' => 1, 'date' => 'd', 'text' => 't'];
            }
        }

        return $data;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class UnionArrayShapesNoDoc
{
    public function getRowFormatBookEntry($abe, $onlyCashRegister = false): array
    {
        $data = [];
        foreach ($abe as $x) {
            if ($onlyCashRegister) {
                $data[] = ['amount' => 1, 'cash_register_id' => 2, 'text' => 't'];
            } else {
                $data[] = ['amount' => 1, 'date' => 'd', 'text' => 't'];
            }
        }

        return $data;
    }
}

?>
