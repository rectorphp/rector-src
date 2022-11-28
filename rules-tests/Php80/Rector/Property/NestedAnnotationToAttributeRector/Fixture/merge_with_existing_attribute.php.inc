<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="api_alert")
 */
#[ApiResource]
class Alert
{
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Table(name: 'api_alert')]
class Alert
{
}

?>
