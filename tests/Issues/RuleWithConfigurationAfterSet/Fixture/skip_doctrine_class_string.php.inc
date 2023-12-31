<?php

namespace Rector\Tests\Issues\RuleWithConfigurationAfterSet\Fixture;

final class SkipDoctrineClassString
{
    public function run(): string
    {
        return 'Doctrine\Common\Collections\ArrayCollection';
    }
}
