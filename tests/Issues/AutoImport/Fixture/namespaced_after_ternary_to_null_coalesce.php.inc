<?php

namespace App;

use shop\product\business\ProductFilter;

class NamespacedAfterTernaryToNullCoalesce
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

namespace App;

use shop\product\business\ProductFilter;

class NamespacedAfterTernaryToNullCoalesce
{
    private function sharedProductFilter(): void
    {
        $page = $_REQUEST['page'] ?? 1;

        $this->brands = Hersteller::getListForProducts(new \ProductFilter());
    }

}

?>