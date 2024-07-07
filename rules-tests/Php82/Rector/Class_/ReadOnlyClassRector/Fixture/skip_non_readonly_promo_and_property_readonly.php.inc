<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

final class SkipNonReadonlyPromoAndPropertyReadonly
{
	public readonly string $b;

    public function __construct(
        public string $a,
        ?string $b = null
    ) {
		  $this->b = $b ?? 'foo';
    }
}
