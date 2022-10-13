<?php

namespace Rector\Core\Tests\Issues\Issue7536\Fixture;

use Symfony\Component\Form\FormInterface;

class SkipDynamicClassConstFetch
{
    private function pocMethod(FormInterface $childType, FormInterface $parentType)
    {
        $parentType->add(
           $childType->methodA(),
           $childType->methodB()::class
        );
    }
}
