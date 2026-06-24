<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector\Fixture;

class EmptyInArray
{
    public static function validateAssetDownload($assetId, array $limitToAssets): bool
    {
        return !(!empty($limitToAssets) && !in_array($assetId, $limitToAssets));
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\NegatedAndsToPositiveOrsRector\Fixture;

class EmptyInArray
{
    public static function validateAssetDownload($assetId, array $limitToAssets): bool
    {
        return empty($limitToAssets) || in_array($assetId, $limitToAssets);
    }
}

?>
