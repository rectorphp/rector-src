<?php

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureTraitAttribute;

#[SomeAttribute(new \ArrayObject([1, 2]))]
trait SkipNewWithArrayInTraitAttribute
{
}
