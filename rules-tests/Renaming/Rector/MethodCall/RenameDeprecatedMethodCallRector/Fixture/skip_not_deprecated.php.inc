<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Source\SomeApiClient;

function skipNotDeprecated(SomeApiClient $apiClient)
{
    return $apiClient->fetchData();
}
