<?php

declare(strict_types=1);

namespace Rector\Tests\Application\ApplicationFileProcessor\Source;

use Rector\Tests\Application\ApplicationFileProcessor\Source\Rules\Money;
use Rector\Tests\Application\ApplicationFileProcessor\Source\ValueObjects\Money as MoneyValue;

final class WithAliasedImportOfSameShortName
{
    public function rule(): Money
    {
        return new Money();
    }

    public function value(): MoneyValue
    {
        return new MoneyValue();
    }
}
