<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinColumns;

final class DoctrineNestedJoinColumnsPromotedProperty
{
    public function __construct(
        /**
         * @JoinColumns({
         *   @JoinColumn(name="entity_id", referencedColumnName="id"),
         *   @JoinColumn(name="entity_type", referencedColumnName="entity_type"),
         * })
         */
        protected $page,
    ) {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinColumns;

final class DoctrineNestedJoinColumnsPromotedProperty
{
    public function __construct(
        #[JoinColumn(name: 'entity_id', referencedColumnName: 'id')]
        #[JoinColumn(name: 'entity_type', referencedColumnName: 'entity_type')]
        protected $page,
    ) {
    }
}

?>
