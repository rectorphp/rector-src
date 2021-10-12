<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class CopyArrayGenericDoc
{
    /**
     * Comment
     * @var array<string, string>
     */
    private array $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }
}

?>
-----
<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class CopyArrayGenericDoc
{
    public function __construct(
        /**
         * Comment
         * @param array<string, string>
         */
        private array $map
    )
    {
    }
}

?>
