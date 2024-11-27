<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorCommentOnUnrelatedReturn
{
    public function run($data, $proxyQuery, $alias): bool
    {
        switch ($data->getValue()) {
            case ProductService::SPECIAL_GESTION_CAPSULE:
                // comment 1
                $proxyQuery->getQueryBuilder()->andWhere($alias . '.collectionCapsule = true');
                break;
            case ProductService::SPECIAL_GESTION_PRESTIGE:
                // comment 2
                $proxyQuery->getQueryBuilder()->andWhere($alias . '.editionPrestige = true');
                break;
        }

        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class MirrorCommentOnUnrelatedReturn
{
    public function run($data, $proxyQuery, $alias): bool
    {
        match ($data->getValue()) {
            // comment 1
            ProductService::SPECIAL_GESTION_CAPSULE => $proxyQuery->getQueryBuilder()->andWhere($alias . '.collectionCapsule = true'),
            // comment 2
            ProductService::SPECIAL_GESTION_PRESTIGE => $proxyQuery->getQueryBuilder()->andWhere($alias . '.editionPrestige = true'),
            default => true,
        };

        return true;
    }
}

?>
