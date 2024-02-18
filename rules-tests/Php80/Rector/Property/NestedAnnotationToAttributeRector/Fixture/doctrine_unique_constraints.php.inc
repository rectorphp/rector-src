<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping\Table;

/**
 * @Table(uniqueConstraints={@\Doctrine\ORM\Mapping\UniqueConstraint(name="some_name")})
 */
class DoctrineUniqueConstraints
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping\Table;

#[Table]
#[\Doctrine\ORM\Mapping\UniqueConstraint(name: 'some_name')]
class DoctrineUniqueConstraints
{
}

?>
