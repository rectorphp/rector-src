<?php

namespace Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Fixture;

final class SkipUnknownClass
{
    /**
     * @var SomeNonExistingService
     */
    private $service;
}
