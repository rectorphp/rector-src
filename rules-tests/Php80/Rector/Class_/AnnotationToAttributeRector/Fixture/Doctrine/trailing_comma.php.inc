<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

final class TrailingComma
{
    /**
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, )
     **/
    protected $someColumn;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

final class TrailingComma
{
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true)]
    protected $someColumn;
}

?>
