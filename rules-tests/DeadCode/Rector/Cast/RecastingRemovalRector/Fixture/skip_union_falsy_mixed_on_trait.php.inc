<?php

trait SkipUnionFalsyMixedOnTrait
{
    public function run(bool $param): bool
    {
        return (bool) ($this->config['allow_404'] ?? false);
    }
}
