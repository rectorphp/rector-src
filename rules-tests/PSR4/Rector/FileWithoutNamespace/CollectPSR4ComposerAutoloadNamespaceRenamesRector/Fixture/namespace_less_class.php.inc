<?php

declare(strict_types=1);

class NamespaceLessClass
{
    public function someMethod()
    {
        $parentClass = parent::someMethod();

        $someClass = new Rector\Tests\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector\Source\SomeClass();
    }
}
