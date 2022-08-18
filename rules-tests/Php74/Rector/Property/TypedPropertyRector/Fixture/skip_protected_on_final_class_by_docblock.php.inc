<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

/**
 * @final
 */
class SkipProtectedOnFinalClassByDocblock
{
    /** @var string */
    protected $foo;
}
