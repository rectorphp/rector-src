<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\NameBooleanOrNullArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\NameBooleanOrNullArgumentRector\Source\Service;

in_array($value, $array, true);

function (Service $service, string $value): void
{
    $service->configure($value, true, null);
    Service::create($value, true, null);
    new Service($value, true, null);
};

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\NameBooleanOrNullArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\NameBooleanOrNullArgumentRector\Source\Service;

in_array($value, $array, strict: true);

function (Service $service, string $value): void
{
    $service->configure($value, strict: true, fallback: null);
    Service::create($value, strict: true, fallback: null);
    new Service($value, strict: true, fallback: null);
};

?>
