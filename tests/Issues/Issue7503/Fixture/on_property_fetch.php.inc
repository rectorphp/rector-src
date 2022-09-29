<?php

namespace Rector\Core\Tests\Issues\Issue7503\Fixture;

final class OnPropertyFetch
{
    private ?int $price = null;
    private int $vat = 1;

    public function run()
    {
        $this->priceWithVat = (int)round($this->price * (1 + ($this->vat / 100)));
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\Issue7503\Fixture;

final class OnPropertyFetch
{
    private const PRICE = null;
    private const VAT = 1;

    public function run()
    {
        $this->priceWithVat = (int)round(self::PRICE * (1 + (self::VAT / 100)));
    }
}

?>
