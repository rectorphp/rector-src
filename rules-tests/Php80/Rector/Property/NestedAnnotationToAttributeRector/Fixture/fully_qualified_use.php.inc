<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Table(indexes={@Index(name="search_idx")}]
 */
final class FullyQualifiedUse
{
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Table]
#[Index(name: 'search_idx')]
final class FullyQualifiedUse
{
}

?>
