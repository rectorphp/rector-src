<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
namespace Rector\Php80\Tests\Rector\Class_\AnnotationToAttributeRector\Fixture;

class Bar
{
    /**
     * @var Collection<int, Foo>
     *
     * @ORM\OrderBy({
     *     "createdAt": "DESC",
     * })
     *
     * @ORM\OneToMany(targetEntity=Foo::class, mappedBy="user", orphanRemoval=true)
     */
    private Collection $foos; 
}
?>
-----
<?php

namespace Rector\Php80\Tests\Rector\Class_\AnnotationToAttributeRector\Fixture;

#[\Doctrine\ORM\Mapping\Entity]
class Bar
{
    /**
     * @var Collection<int, Foo>
     */
    #[\Doctrine\ORM\Mapping\OrderBy(['createdAt' => 'DESC'])]
    #[\Doctrine\ORM\Mapping\OneToMany(targetEntity: Foo::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $foos;
?>
