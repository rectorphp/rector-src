<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanOr\RepeatedOrEqualToInArrayRector\Fixture;

final class SkipShortCircuitDependentCall
{
    public function run(?string $locale, ?SomeTranslations $translations): bool
    {
        if (null === $locale || null === $translations || null === $translations->getTranslation($locale)) {
            return true;
        }

        return false;
    }
}

final class SomeTranslations
{
    public function getTranslation(string $locale): ?string
    {
        return null;
    }
}
