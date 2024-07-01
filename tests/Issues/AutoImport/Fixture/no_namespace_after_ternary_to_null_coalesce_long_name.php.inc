<?php

use shop\product\business\ProductFilter;

class NoNamespaceAfterTernaryToNullCoalesceLongName
{
    private function sharedProductFilter(): void
    {
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;

        $this->brands = Hersteller::getListForProducts(new \some\different\ProductFilter());
    }

}

?>
-----
<?php

use shop\product\business\ProductFilter;

class NoNamespaceAfterTernaryToNullCoalesceLongName
{
    private function sharedProductFilter(): void
    {
        $page = $_REQUEST['page'] ?? 1;

        $this->brands = Hersteller::getListForProducts(new \some\different\ProductFilter());
    }

}

?>
