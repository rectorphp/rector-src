<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class CombinePromoAndPropertyReadonly
{
	public readonly string $b;

    public function __construct(
        public readonly string $a,
        ?string $b = null
    ) {
		  $this->b = $b ?? 'foo';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class CombinePromoAndPropertyReadonly
{
	public string $b;

    public function __construct(
        public string $a,
        ?string $b = null
    ) {
		  $this->b = $b ?? 'foo';
    }
}

?>
