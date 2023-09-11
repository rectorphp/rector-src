<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\ValidatedElement;
use Symfony\Component\Validator\Constraints as Assert;

final class CopyDoc
{
    /**
     * @var ValidatedElement[]
     * @Assert\Valid()
     * @Assert\NotBlank()
     */
    public array $z = [];

    /**
     * @param ValidatedElement[] $z
     */
    public function __construct(array $z = [])
    {
        $this->z = $z;
    }
}
?>
-----
<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\ValidatedElement;
use Symfony\Component\Validator\Constraints as Assert;

final class CopyDoc
{
    /**
     * @param ValidatedElement[] $z
     */
    public function __construct(
        /**
         * @Assert\Valid()
         * @Assert\NotBlank()
         */
        public array $z = []
    )
    {
    }
}
?>
