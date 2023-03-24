<?php

namespace Rector\Tests\Php81\Rector\ClassMethod\NewInInitializerRector\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class Carrier
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $id;

    public function __construct(
        ?Ulid $id = null,
    ) {
        $this->id = $id ?? new Ulid();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\ClassMethod\NewInInitializerRector\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class Carrier
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'ulid', unique: true)]
        private Ulid $id = new Ulid(),
    ) {
    }
}

?>
