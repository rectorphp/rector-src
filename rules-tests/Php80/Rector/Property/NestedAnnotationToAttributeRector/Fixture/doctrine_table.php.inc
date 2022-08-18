<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_account_role",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="user_account_role_unique", columns={"user_account_id", "list_user_role_id"}),
 *          @ORM\UniqueConstraint(name="second"),
 *          @ORM\UniqueConstraint(name="third"),
 *      },
 *     indexes={
 *          @ORM\Index(name="some_name")
 *     }
 * )
 */
class DoctrineTable
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'user_account_role')]
#[ORM\Index(name: 'some_name')]
#[ORM\UniqueConstraint(name: 'user_account_role_unique', columns: ['user_account_id', 'list_user_role_id'])]
#[ORM\UniqueConstraint(name: 'second')]
#[ORM\UniqueConstraint(name: 'third')]
class DoctrineTable
{
}

?>
