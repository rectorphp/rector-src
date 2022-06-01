<?php

use My\Namespace\UnknownClass;

final class SkipUnknownClassInVar
{
    private $otherClass;

    public function getOtherClass()
    {
        return $this->otherClass;
    }

    public function setOtherClass($otherClass): void
    {
        /** @var UnknownClass $otherClass */
        $this->otherClass = $otherClass;
    }
}
