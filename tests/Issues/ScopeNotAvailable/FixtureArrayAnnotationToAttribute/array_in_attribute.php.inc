<?php

namespace Rector\Tests\Issues\FixtureArrayAnnotationToAttribute\Fixture;

use Doctrine\ORM\Mapping as ORM;

class ArrayInAttribute
{
    public function __construct(
        /**
         * @ORM\ManyToOne(targetEntity=OfferPrice::class, inversedBy="myEntities", cascade={"persist"})
         */
        private OfferPrice $offerPrice
    )
    {
    }

}

?>
-----
<?php

namespace Rector\Tests\Issues\FixtureArrayAnnotationToAttribute\Fixture;

use Doctrine\ORM\Mapping as ORM;

class ArrayInAttribute
{
    public function __construct(
        #[ORM\ManyToOne(targetEntity: OfferPrice::class, inversedBy: 'myEntities', cascade: ['persist'])]
        private OfferPrice $offerPrice
    )
    {
    }

}

?>
