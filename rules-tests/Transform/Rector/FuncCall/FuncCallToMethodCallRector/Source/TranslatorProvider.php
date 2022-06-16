<?php

declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\FuncCall\FuncCallToMethodCallRector\Source;

abstract class TranslatorProvider
{
    private $translator;

    final public function getTranslator(): SomeTranslator
    {
        return $this->translator;
    }
}
