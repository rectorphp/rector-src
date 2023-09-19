<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class FallbackReturnArray
{
    public function groupOffers($offers)
    {
        if (count($offers) == 0) {
            return [];
        }

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

final class FallbackReturnArray
{
    /**
     * @return mixed[]
     */
    public function groupOffers($offers): array
    {
        if (count($offers) == 0) {
            return [];
        }

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
