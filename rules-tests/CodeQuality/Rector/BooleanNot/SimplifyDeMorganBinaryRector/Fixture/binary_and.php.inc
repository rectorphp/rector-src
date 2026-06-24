<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector\Fixture;

class BinaryAnd
{
    public static function validateAssetDownload($assetId, array $limitToAssets): bool
    {
        return !(!empty($limitToAssets) && !in_array($assetId, $limitToAssets));
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector\Fixture;

class BinaryAnd
{
    public static function validateAssetDownload($assetId, array $limitToAssets): bool
    {
        return empty($limitToAssets) || in_array($assetId, $limitToAssets);
    }
}

?>
