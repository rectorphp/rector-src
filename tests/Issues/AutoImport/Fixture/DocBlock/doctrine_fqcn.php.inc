<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

/**
 * @\Doctrine\ORM\Mapping\Entity(repositoryClass="App\Some\Class")
 */
class DoctrineFQCN
{
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: 'App\Some\Class')]
class DoctrineFQCN
{
}

?>
