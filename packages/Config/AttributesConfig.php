<?php

declare(strict_types=1);

namespace Rector\Config;

use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Symfony\Set\SymfonySetList;

/**
 * @api
 */
final class AttributesConfig
{
    public function __construct(
        private readonly RectorConfig $rectorConfig
    ) {
    }

    public function symfony(): self
    {
        $this->rectorConfig->sets([SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES]);

        return $this;
    }

    public function gedmo(): self
    {
        $this->rectorConfig->sets([DoctrineSetList::GEDMO_ANNOTATIONS_TO_ATTRIBUTES]);

        return $this;
    }

    public function doctrine(): self
    {
        $this->rectorConfig->sets([DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES]);

        return $this;
    }

    public function mongodb(): self
    {
        $this->rectorConfig->sets([DoctrineSetList::MONGODB__ANNOTATIONS_TO_ATTRIBUTES]);

        return $this;
    }
}
