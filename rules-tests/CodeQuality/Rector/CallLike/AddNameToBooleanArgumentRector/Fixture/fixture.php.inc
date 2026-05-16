<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Source\Service;

in_array($value, $array, true);

function (Service $service, string $value): void
{
    $service->configure($value, true);
    Service::create($value, true);
    new Service($value, true);
};

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Fixture;

use Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Source\Service;

in_array($value, $array, strict: true);

function (Service $service, string $value): void
{
    $service->configure($value, strict: true);
    Service::create($value, strict: true);
    new Service($value, strict: true);
};

?>
