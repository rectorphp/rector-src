<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="foos",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="name_unique", columns={"name", "name2"}),
 *     }
 * )
 */
namespace Rector\Php80\Tests\Rector\Class_\AnnotationToAttributeRector\Fixture;

class Foo
{
    /**
     * @ORM\Column(type="string")
     */
    private string $name;
    
    /**
     * @ORM\Column(type="string")
     */
    private string $name2;    
}
?>
-----
<?php

namespace Rector\Php80\Tests\Rector\Class_\AnnotationToAttributeRector\Fixture;

+#[\Doctrine\ORM\Mapping\Entity]
+#[\Doctrine\ORM\Mapping\Table(name: 'foos')]
+#[\Doctrine\ORM\Mapping\UniqueContraint(name: "name_unique", columns=["name", "name2"])]
+class Foo
+{
+    #[\Doctrine\ORM\Mapping\Column(type: 'string')]
+    private string $name;
+    #[\Doctrine\ORM\Mapping\Column(type: 'string')]
+    private string $name2;
 }

?>
