<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Source\SomeApiClient;

function skipNoSuggestion(SomeApiClient $apiClient)
{
    // @deprecated description has no "use newMethod()" hint
    return $apiClient->legacyData();
}
