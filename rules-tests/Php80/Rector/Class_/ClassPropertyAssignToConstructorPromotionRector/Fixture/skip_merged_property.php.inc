<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class SkipMergedProperty
{
    /**
     * @var array
     */
    private $metadata = [
        'config' => [],
    ];

    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(array $metadata)
    {
        $this->metadata = $metadata;

        if (!isset($this->metadata['permission'])) {
            $this->metadata['permission'] = [];
        }

        $permission = $metadata['permission'];
    }
}

?>
-----
<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class SkipMergedProperty
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(private array $metadata)
    {
        if (!isset($this->metadata['permission'])) {
            $this->metadata['permission'] = [];
        }

        $permission = $this->metadata['permission'];
    }
}

?>

