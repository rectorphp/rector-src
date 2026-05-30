<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Source\SomeApiClient;

function renameUseInstead(SomeApiClient $apiClient)
{
    return $apiClient->getData();
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Source\SomeApiClient;

function renameUseInstead(SomeApiClient $apiClient)
{
    return $apiClient->fetchData();
}

?>
