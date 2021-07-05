<?php

declare(strict_types=1);

namespace Rector\Doctrine\Tests\Set\DoctrineORM29Set\Fixture;

use Doctrine\ORM\Mapping\Entity;

/**
 * @Entity
 */
class Employee extends Person
{
    public const TYPE = 'employee';
}

?>
<?php

declare(strict_types=1);

namespace Rector\Doctrine\Tests\Set\DoctrineORM29Set\Fixture;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;

/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({Person::TYPE = Person::class, Employee::TYPE = Employee::class})
 */
class Person
{
    public const TYPE = 'person';
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Doctrine\Tests\Set\DoctrineORM29Set\Fixture;

use Doctrine\ORM\Mapping\Entity;

#[\Doctrine\ORM\Mapping\Entity]
class Employee extends Person
{
    public const TYPE = 'employee';
}

?>
<?php

declare(strict_types=1);

namespace Rector\Doctrine\Tests\Set\DoctrineORM29Set\Fixture;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;

#[\Doctrine\ORM\Mapping\Entity]
#[\Doctrine\ORM\Mapping\InheritanceType(value: 'SINGLE_TABLE')]
#[\Doctrine\ORM\Mapping\DiscriminatorColumn(name: 'discr', type: 'string')]
#[\Doctrine\ORM\Mapping\DiscriminatorMap(value: [Person::TYPE => Person::class, Employee::TYPE => Employee::class])]
class Person
{
    public const TYPE = 'person';
}

?>
