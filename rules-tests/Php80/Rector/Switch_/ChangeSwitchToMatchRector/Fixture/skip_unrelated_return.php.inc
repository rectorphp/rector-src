<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class DemoFile
{
    public function run(): bool
    {
        switch ($data->getValue()) {
            case ProductService::SPECIAL_GESTION_CAPSULE:
                $proxyQuery->getQueryBuilder()->andWhere($alias . '.collectionCapsule = true');
                break;
            case ProductService::SPECIAL_GESTION_PRESTIGE:
                $proxyQuery->getQueryBuilder()->andWhere($alias . '.editionPrestige = true');
                break;
            case ProductService::SPECIAL_GESTION_MARGIN:
                $proxyQuery->getQueryBuilder()->andWhere($alias . '.criticalMargin = true');
                break;
        }

        return true;
    }
}

?>
