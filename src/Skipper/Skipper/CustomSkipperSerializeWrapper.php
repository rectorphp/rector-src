<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use Rector\Skipper\Skipper\Custom\CustomSkipperInterface;

readonly class CustomSkipperSerializeWrapper
{
    public function __construct(
        public CustomSkipperInterface $customSkipper,
    ) {
    }

    public function __serialize(): array
    {
        return [$this->customSkipper::IMPLEMENTATION_HASH];
    }
}
