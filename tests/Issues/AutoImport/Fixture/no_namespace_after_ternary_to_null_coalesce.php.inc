<?php

use shop\product\business\ProductFilter;

class NoNamespaceAfterTernaryToNullCoalesce
{
    private function sharedProductFilter(): void
    {
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;

        $this->brands = Hersteller::getListForProducts(new \ProductFilter());
    }

}

?>
-----
<?php

use shop\product\business\ProductFilter;

class NoNamespaceAfterTernaryToNullCoalesce
{
    private function sharedProductFilter(): void
    {
        $page = $_REQUEST['page'] ?? 1;

        $this->brands = Hersteller::getListForProducts(new \ProductFilter());
    }

}

?>
