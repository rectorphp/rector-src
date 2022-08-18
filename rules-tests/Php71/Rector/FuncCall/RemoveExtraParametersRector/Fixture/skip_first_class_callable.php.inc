<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

final class SkipFirstClassCallable
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_seo_title', $this->getTitle(...), [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function getTitle(): string
    {
        return 'title';
    }
}
