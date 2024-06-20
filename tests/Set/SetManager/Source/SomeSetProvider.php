<?php

declare(strict_types=1);

namespace Rector\Tests\Set\SetManager\Source;

use Rector\Set\Contract\SetInterface;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\ComposerTriggeredSet;
use Rector\Symfony\Set\TwigSetList;

final class SomeSetProvider implements SetProviderInterface
{
    /**
     * @return SetInterface[]
     */
    public function provide(): array
    {
        return [
            new ComposerTriggeredSet('twig', 'twig/twig', '1.12', TwigSetList::TWIG_112)
        ];
    }
}
