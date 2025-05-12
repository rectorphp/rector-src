<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

function run(\Closure $closure): void
{
}

run(
	#[SomeAttribute(['array-argument'])]
	fn(): null => null,
);