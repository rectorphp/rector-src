<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Annotation;

use Rector\Contract\Rector\RectorInterface;

final readonly class RectorsChangelogResolver
{
    public function __construct(
        private AnnotationExtractor $annotationExtractor
    ) {
    }

    /**
     * @param array<class-string<RectorInterface>> $rectorClasses
     * @return array<class-string, string|null>
     */
    public function resolve(array $rectorClasses): array
    {
        $rectorClassesToChangelogUrls = [];
        foreach ($rectorClasses as $rectorClass) {
            $changelogUrl = $this->annotationExtractor->extractAnnotationFromClass($rectorClass, '@changelog');
            $rectorClassesToChangelogUrls[$rectorClass] = $changelogUrl;
        }

        return $rectorClassesToChangelogUrls;
    }
}
