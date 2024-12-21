<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source;

use Symfony\Component\Serializer\Attribute\Groups;

class Booking
{
    /**
     * @var int
     *
     * @Groups({"Booking.id"})
     */
    private $id;

    /**
     * @var int
     *
     * @Source\Groups({"Booking.id"})
     */
    private $id2;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source;

use Symfony\Component\Serializer\Attribute\Groups;

class Booking
{
    /**
     * @var int
     */
    #[Groups(['Booking.id'])]
    private $id;

    /**
     * @var int
     *
     * @Source\Groups({"Booking.id"})
     */
    private $id2;
}

?>
