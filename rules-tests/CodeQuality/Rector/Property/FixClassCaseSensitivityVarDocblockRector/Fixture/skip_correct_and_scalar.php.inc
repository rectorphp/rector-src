<?php

namespace Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector\Source\AutoMailingService;

final class SkipCorrectAndScalar
{
    /**
     * @var AutoMailingService
     */
    private $service;

    /**
     * @var string
     */
    private $name;

    public function run()
    {
        /** @var self $self */
        $self = $this;

        return $self;
    }
}
