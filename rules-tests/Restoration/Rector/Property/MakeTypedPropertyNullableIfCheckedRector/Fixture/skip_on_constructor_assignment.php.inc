<?php

namespace Rector\Tests\Restoration\Rector\Property\MakeTypedPropertyNullableIfCheckedRector\Fixture;

class SkipOnConstructorAssignment
{
    private string $message;

    public function __construct()
    {
        $this->message = 'foo';
    }

    private function getMessage(): ?string
    {
        if (!$this->message) {
            return null;
        }

        return $this->message;
    }
}
