<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class UnionReservationArrayShapesNoDoc
{
    public function getReservations($items, $withInvoice = false)
    {
        $data = [];
        foreach ($items as $item) {
            if ($withInvoice) {
                $data[] = [
                    'currency' => 'EUR',
                    'creation_date' => 'd',
                    'guarantee' => 1,
                    'invoice_recipient_id' => 5,
                    'invoice_free_text' => 't',
                    'status' => 'ok',
                    'type' => 'a',
                ];
            } else {
                $data[] = [
                    'currency' => 'EUR',
                    'creation_date' => 'd',
                    'guarantee' => 1,
                    'status' => 'ok',
                    'type' => 'a',
                ];
            }
        }

        return $data;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class UnionReservationArrayShapesNoDoc
{
    public function getReservations($items, $withInvoice = false): array
    {
        $data = [];
        foreach ($items as $item) {
            if ($withInvoice) {
                $data[] = [
                    'currency' => 'EUR',
                    'creation_date' => 'd',
                    'guarantee' => 1,
                    'invoice_recipient_id' => 5,
                    'invoice_free_text' => 't',
                    'status' => 'ok',
                    'type' => 'a',
                ];
            } else {
                $data[] = [
                    'currency' => 'EUR',
                    'creation_date' => 'd',
                    'guarantee' => 1,
                    'status' => 'ok',
                    'type' => 'a',
                ];
            }
        }

        return $data;
    }
}

?>
