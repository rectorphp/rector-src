<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

class DoctrineNestedAttributes
{
    /**
     * @ORM\JoinTable(name="user_group_user",
     *      joinColumns={@ORM\JoinColumn(name="user_group_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    private $users;
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

class DoctrineNestedAttributes
{
    #[ORM\JoinTable(name: 'user_group_user')]
    #[ORM\JoinColumn(name: 'user_group_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private $users;
}

?>
