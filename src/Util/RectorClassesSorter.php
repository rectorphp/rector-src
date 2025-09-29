<?php

declare(strict_types=1);

namespace Rector\Util;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Contract\Rector\RectorInterface;
use Rector\PostRector\Contract\Rector\PostRectorInterface;

final class RectorClassesSorter
{
    /**
     * @param array<class-string<RectorInterface|PostRectorInterface>> $rectorClasses
     * @return array<class-string<RectorInterface|PostRectorInterface>>
     */
    public static function sort(array $rectorClasses): array
    {
        $rectorClasses = array_unique($rectorClasses);

        $mainRectorClasses = array_filter(
            $rectorClasses,
            fn (string $rectorClass): bool => is_a($rectorClass, RectorInterface::class, true)
        );
        sort($mainRectorClasses);

        if (SimpleParameterProvider::provideBoolParameter(Option::INCLUDE_POST_RECTORS_IN_REPORTS)) {
            $postRectorClasses = array_filter(
                $rectorClasses,
                fn (string $rectorClass): bool => is_a($rectorClass, PostRectorInterface::class, true)
            );
            sort($postRectorClasses);

            return array_merge($mainRectorClasses, $postRectorClasses);
        }

        return $mainRectorClasses;
    }
}
