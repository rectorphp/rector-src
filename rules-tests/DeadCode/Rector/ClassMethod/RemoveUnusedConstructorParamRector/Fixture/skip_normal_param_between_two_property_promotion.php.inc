<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

use stdClass;

final class SkipNormalParamBetweenTwoPropertyPromotion
{
	public function __construct(private stdClass $demofile, stdClass $std, private stdClass $demofile2)
    {
    }
}
