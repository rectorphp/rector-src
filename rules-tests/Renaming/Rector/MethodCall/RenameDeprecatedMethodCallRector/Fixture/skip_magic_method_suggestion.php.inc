<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Fixture;

use Rector\Tests\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector\Source\MagicMethodApiClient;

function skipMagicMethodSuggestion(MagicMethodApiClient $apiClient)
{
    // suggested fetchData() exists only as a @method magic method, not a real native one
    return $apiClient->getData();
}
