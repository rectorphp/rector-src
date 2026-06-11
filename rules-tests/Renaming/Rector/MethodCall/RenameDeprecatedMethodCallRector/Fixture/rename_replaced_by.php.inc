<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Source\SomeApiClient;

function renameReplacedBy(SomeApiClient $apiClient)
{
    return $apiClient->loadData();
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Source\SomeApiClient;

function renameReplacedBy(SomeApiClient $apiClient)
{
    return $apiClient->fetchData();
}

?>
