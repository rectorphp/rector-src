<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector\Source\Service;

function (Service $service, string $value): void
{
    $service->configure($value, null);
    Service::create($value, null);
    new Service($value, null);
};

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector\Source\Service;

function (Service $service, string $value): void
{
    $service->configure($value, default: null);
    Service::create($value, default: null);
    new Service($value, default: null);
};

?>
