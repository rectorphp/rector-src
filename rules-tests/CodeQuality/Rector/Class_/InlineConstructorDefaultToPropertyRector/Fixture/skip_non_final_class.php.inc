<?php

class ValidatedBase
{
    private bool $validated = false;

    protected function __construct()
    {
        $this->validate();
        $this->validated = true;
    }

    private function validate(): void
    {
    }
}

final class SkipsParentConstructor extends ValidatedBase
{
    public function __construct()
    {
    }
}
