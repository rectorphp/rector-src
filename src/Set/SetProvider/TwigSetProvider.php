<?php

declare(strict_types=1);

namespace Rector\Set\SetProvider;

use Rector\Set\Contract\SetInterface;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\Enum\SetGroup;
use Rector\Set\ValueObject\ComposerTriggeredSet;
use Rector\Symfony\Set\TwigSetList;

/**
 * Temporary location, move to rector-symfony package once this is merged
 * @experimental 2024-06
 */
final class TwigSetProvider implements SetProviderInterface
{
    /**
     * @return SetInterface[]
     */
    public function provide(): array
    {
        $sets = [];
        $sets[] = new ComposerTriggeredSet(SetGroup::TWIG, 'twig/twig', '1.12', TwigSetList::TWIG_112);

        return $sets;
    }
}
