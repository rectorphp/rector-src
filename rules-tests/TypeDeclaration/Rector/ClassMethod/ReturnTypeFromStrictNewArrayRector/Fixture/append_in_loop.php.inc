<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class AppendInLoop
{
    public function groupOffers($offers)
    {
        $groupOffers = [];
        $groupedOffers = [];

        foreach ($offers as $offer) {
            $groupedOffers[$offer->groupid.'--'.$offer->tarifid] = $offer;
            $groupedOffers[$offer->groupid.'--'.$offer->tarifid]->groupedOffers = $groupOffers;
        }

        return $groupedOffers;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class AppendInLoop
{
    /**
     * @return mixed[]
     */
    public function groupOffers($offers): array
    {
        $groupOffers = [];
        $groupedOffers = [];

        foreach ($offers as $offer) {
            $groupedOffers[$offer->groupid.'--'.$offer->tarifid] = $offer;
            $groupedOffers[$offer->groupid.'--'.$offer->tarifid]->groupedOffers = $groupOffers;
        }

        return $groupedOffers;
    }
}

?>
