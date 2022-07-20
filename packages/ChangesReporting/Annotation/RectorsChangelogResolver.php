<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Annotation;

use Rector\Core\Contract\Rector\RectorInterface;

final class RectorsChangelogResolver
{
    public function __construct(
        private readonly AnnotationExtractor $annotationExtractor
    ) {
    }

    /**
     * @param array<class-string<RectorInterface>> $rectorClasses
     * @return array<class-string, string>
     */
    public function resolve(array $rectorClasses): array
    {
        $rectorClassesToChangelogUrls = $this->resolveIncludingMissing($rectorClasses);
        return array_filter($rectorClassesToChangelogUrls);
    }

    /**
     * @param array<class-string<RectorInterface>> $rectorClasses
     * @return array<class-string, string|null>
     */
    public function resolveIncludingMissing(array $rectorClasses): array
    {
        $rectorClassesToChangelogUrls = [];
        foreach ($rectorClasses as $rectorClass) {
            $rectorClassesToChangelogUrls[$rectorClass] = $this->annotationExtractor->extractAnnotationFromClass(
                $rectorClass,
                '@changelog'
            );
        }

        return $rectorClassesToChangelogUrls;
    }
}
