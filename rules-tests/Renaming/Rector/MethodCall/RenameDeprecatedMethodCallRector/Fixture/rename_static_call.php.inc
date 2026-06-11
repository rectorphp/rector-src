<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Source\SomeApiClient;

function renameStaticCall()
{
    return SomeApiClient::makeOld();
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Source\SomeApiClient;

function renameStaticCall()
{
    return SomeApiClient::make();
}

?>
